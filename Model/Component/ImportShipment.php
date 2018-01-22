<?php

namespace Cleargo\Integrationframeworks\Model\Component;

use Magento\Sales\Model\Order\Shipment;
use Psr\Log\LoggerInterface;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Convert\Order as ConvertOrder;

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


    public function __construct(LoggerInterface $logger, DirectoryList $directoryList,
                                OrderFactory $orderFactory, ConvertOrder $convertOrder)
    {
        $this->logger = $logger;
        $this->directoryList = $directoryList;
        $this->orderFactory = $orderFactory;
        $this->convertOrder = $convertOrder;
    }

    public function execute() {
        var_dump('Import Shipment Start');

        // TODO: Update Shipment by xml, move the updated xml file to archive
        /*var_dump($this->relationParams);
        var_dump($this->websiteId);
        var_dump($this->storeId);*/
        $this->createOrderShipmentByXml();


        var_dump('Import Shipment Executed');
    }

    public function createOrderShipmentByXml() {
        $importFolderDir = $this->directoryList->getRoot() . "/var/nav/import/shipment/";
        $fileLists = array_diff(scandir($importFolderDir), array('.', '..'));
        var_dump($fileLists);

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

                }


            } catch (\Exception $e) {
                if ($this->scheduleLogLevel == "INFO") {
                    $this->logger->info($e);
                } else {
                    $this->logger->debug($e);
                }
            }

        }

        //$xmlObj = simplexml_load_file($importXmlFolderPath);
        die('dtest');


        $xmlData = $xmlObj->getNode();
        var_dump($xmlData);
        $sentEmailToCustomer = $this->relationParams->sent_email_to_customer;
        if ($sentEmailToCustomer == 'Y') {
            $emailSent = true;
        } else {
            $emailSent = false;
        }

        var_dump('createOrderShipmentByXml');
        return;
        $exportPath = $this->relationParams->export_path;
        $outputDir = $this->directoryList->getRoot() . $exportPath;

        // TODO: Get Order Collection
        $this->creditmemoCollection = $this->getCreditmemoCollection();

        var_dump('Colleciton count: ' . $this->creditmemoCollection->count());

        // TODO: Export Order Xml
        foreach ($this->creditmemoCollection as $creditmemo) {
            $currentTime = time();
            $fileTime = date("Ymd_HisS", $currentTime);
            $fileName = 'creditmemo_export_' . $creditmemo->getIncrementId() . '_' . $fileTime . '.xml';
            //var_dump($fileName);

            $content = "<response>&#xA;";
            $content .=  $creditmemo->toXml([], null, null, false);
            $content .= "</response>";
            $xml = new \SimpleXMLElement($content);

            // TODO: generate xml for each order
            $outputFile = fopen($outputDir . $fileName, "w");
            try {
                fwrite($outputFile, $xml->asXML());
                fclose($outputFile);
                $creditmemo->setNavLastSyncAt(date("Y-m-d H:i:s", $currentTime));
                $creditmemo->getResource()->saveAttribute($creditmemo, 'nav_last_sync_at');
            } catch (Exception $e) {
                $this->logger->addDebug($e->getMessage());
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