<?php

namespace Cleargo\Integrationframeworks\Model\Component;

use Magento\Sales\Model\Order\Shipment;
use Cleargo\Integrationframeworks\Logger\Logger;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Convert\Order as ConvertOrder;
use Magento\Sales\Model\Order\Shipment\TrackFactory;
use Magento\Shipping\Model\ShipmentNotifier;

class ImportShipment
{
    protected $logger;

    protected $creditmemoCollection;

    protected $relationParams;

    protected $websiteId;

    protected $storeId;

    protected $scheduleLogLevel;

    protected $directoryList;

    protected $orderFactory;

    protected $convertOrder;

    protected $shipmentTrackFactory;
    
    protected $shipmentNotifier;

    const IMPORT_FOLDER_DIRECTORY = "/var/nav/import/shipment/";

    const EXPORT_FOLDER_DIRECTORY = "/var/nav/import/archive/shipment/";


    public function __construct(Logger $logger, DirectoryList $directoryList,
                                OrderFactory $orderFactory, ConvertOrder $convertOrder,
                                TrackFactory $shipmentTrackFactory, ShipmentNotifier $shipmentNotifier)
    {
        $this->logger = $logger;
        $this->directoryList = $directoryList;
        $this->orderFactory = $orderFactory;
        $this->convertOrder = $convertOrder;
        $this->shipmentTrackFactory = $shipmentTrackFactory;
        $this->shipmentNotifier = $shipmentNotifier;
    }

    public function execute() {
        //var_dump('Import Shipment Start');

        // TODO: Update Shipment by xml, move the updated xml file to archive
        /*var_dump($this->relationParams);
        var_dump($this->websiteId);
        var_dump($this->storeId);*/
        $this->createOrderShipmentByXml();


        //var_dump('Import Shipment Executed');
    }

    public function createOrderShipmentByXml() {
        $importFolderDir = $this->directoryList->getRoot() . self::IMPORT_FOLDER_DIRECTORY;
        $fileLists = array_diff(scandir($importFolderDir), array('.', '..'));
        //var_dump($fileLists);

        if (count($fileLists)) {
            $this->logger->info("ImportShipment: " . count($fileLists) . " shipment file(s) processed");
        } else {
            $this->logger->info("ImportShipment: There are no Shipment for import");
        }

        foreach ($fileLists as $fileName) {
            // TODO: load order model by order increment id and create shipment with tracking code
            try {
                $xmlObj = simplexml_load_file($importFolderDir.$fileName);
                $order = $xmlObj->order;
                $tracksItem = $xmlObj->tracks->item;

                // Order Data
                $orderIncrementId = $order->increment_id;
                $orderStatus = $order->status;

                // Shipment Data
                $trackNumber = $tracksItem->track_number;
                $trackTitle = $tracksItem->title;
                $trackCarrierCode = $tracksItem->carrier_code;

                $order = $this->orderFactory->create()->loadByIncrementId($orderIncrementId);
                if ($order->canShip()) {
                    // TODO: loop item data and create shipment for each item
                    $shipment = $this->convertOrder->toShipment($order);

                    // Loop through order items
                    foreach ($order->getAllItems() AS $orderItem) {
                        // Check if order item has qty to ship or is virtual
                        if (!$orderItem->getQtyToShip() || $orderItem->getIsVirtual()) {
                            continue;
                        }
                        $qtyShipped = $orderItem->getQtyToShip();
                        // Create shipment item with qty
                        $shipmentItem = $this->convertOrder->itemToShipmentItem($orderItem)
                            ->setQty($qtyShipped);
                            //->setCarrierCode($trackCarrierCode)
                            //->setTitle($trackTitle)
                            //->setNumber($trackNumber);
                        // Add shipment item to shipment
                        $shipment->addItem($shipmentItem);
                    }

                    // Add Shipment Tracking info
                    $shipmentTrack = $this->shipmentTrackFactory->create();
                    $shipmentTrack->setCarrierCode($trackCarrierCode)
                                  ->setTitle($trackTitle)
                                  ->setNumber($trackNumber);
                    $shipment->addTrack($shipmentTrack);

                    // Register shipment
                    $shipment->register();
                    $shipment->getOrder()->setIsInProcess(true);

                    // Save created shipment and order
                    $shipment->save();
                    $shipment->getOrder()->save();

                    // Send email
                    //$this->shipmentNotifier->notify($shipment);
                    $shipment->save();
                    $this->logger->info("ImportShipment: Shipment file(".$fileName.") processed and shipment created for Order(id: " . $orderIncrementId . ")");

                    // TODO: Move the successfully read file into archive folder
                    rename($importFolderDir.$fileName, $this->directoryList->getRoot().self::EXPORT_FOLDER_DIRECTORY.$fileName);

                } else {
                    $this->logger->info("ImportShipment: Shipment file(".$fileName.") processed and shipment cannot be created for Order(id: " . $orderIncrementId . ")");
                }
            } catch (\Exception $e) {
                if ($this->scheduleLogLevel == "INFO") {
                    $this->logger->info($e);
                } else {
                    $this->logger->debug($e);
                }
            }
        }
    }

    public function setRelationParams($params) {
        $this->relationParams = json_decode($params);
        return $this;
    }

    public function setWebsiteId($websiteId) {
        $this->websiteId = $websiteId;
        return $this;
    }

    public function setStoreId($storeId) {
        $this->storeId = $storeId;
        return $this;
    }

    public function setScheduleLogLevel($scheduleLogLevel) {
        $this->scheduleLogLevel = $scheduleLogLevel;
        return $this;
    }

}