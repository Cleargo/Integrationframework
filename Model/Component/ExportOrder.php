<?php

namespace Cleargo\Integrationframeworks\Model\Component;

use Cleargo\Integrationframeworks\Logger\Logger;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Sales\Model\OrderFactory;
use Magento\Framework\Filesystem\Io\File;

class ExportOrder
{
    protected $logger;

    protected $orderRepository;

    protected $searchCriteriaBuilder;

    protected $orderCollection;

    protected $relationParams;

    protected $filterBuilder;

    protected $websiteId;

    protected $storeId;

    protected $scheduleLogLevel;

    protected $directoryList;
    
    protected $orderFactory;

    protected $io;


    public function __construct(Logger $logger, OrderRepositoryInterface $orderRepository,
                                SearchCriteriaBuilder $searchCriteriaBuilder, FilterBuilder $filterBuilder,
                                DirectoryList $directoryList, OrderFactory $orderFactory,
                                File $io)
    {
        $this->logger = $logger;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->directoryList = $directoryList;
        $this->orderFactory = $orderFactory;
        $this->io = $io;
    }

    public function execute() {
        var_dump('Export Order Start');
        $this->exportOrderXml();
        var_dump('Export Order Executed');
    }

    public function exportOrderXml() {
        $orderStatus = $this->relationParams->order_status;
        $exportPath = $this->relationParams->export_path;
        $outputDir = $this->directoryList->getRoot() . $exportPath;
        $archiveDir = $outputDir . 'archive/';

        $this->orderCollection = $this->getOrderCollection($orderStatus);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $addressRenderer = $objectManager->create('Magento\Sales\Model\Order\Address\Renderer');

        if (!$this->orderCollection->count()) {
            $this->logger->info("ExportOrder: There are no Order for export");
            return;
        } else {
            // Create directory on local server
            if (!is_dir($outputDir)) {
                $result = $this->io->mkdir($outputDir, 0775);
                if ($result) {
                    $this->logger->info('Directory created on local server: ' . $outputDir);
                } else {
                    $this->logger->info('Fail to create directory on local server: ' . $outputDir);
                }
            }
            // Create directory for archive on local server
            if (!is_dir($archiveDir)) {
                $archiveResult = $this->io->mkdir($archiveDir, 0775);
                if ($archiveResult) {
                    $this->logger->info('Directory for archive created on local server: ' . $archiveDir);
                } else {
                    $this->logger->info('Fail to create directory for archive on local server: ' . $archiveDir);
                }
            }
            $this->logger->info("ExportOrder: " . $this->orderCollection->count() . " order(s) processed");
        }

        // Export Order Xml
        foreach ($this->orderCollection as $order) {
            $currentTime = time();
            $fileTime = date("Ymd_HisS", $currentTime);
            $fileName = 'order_export_' . $order->getIncrementId() . '_' . $fileTime . '.xml';
            //var_dump($fileName);

            $content = "<response>&#xA;";
            $content .=  $order->toXml([], null, null, false);

            $shippingAddress = $order->getShippingAddress();
            if ($shippingAddress) {
                $content .= "<address>" . $addressRenderer->format($order->getShippingAddress(), 'oneline') . "</address>&#xA;";
            }
            if ($shippingAddress->getTelephone()) {
                $content .= "<telephone>" . $order->getShippingAddress()->getTelephone() . "</telephone>&#xA;";
            }

            // Todo:: getOrderItem, temp solution unset object data in item and call toXml
            $items = $order->getAllItems();
            $content .= "<items>&#xA;";
            foreach ($items as $item) {
                foreach ($item->getData() as $label => $value) {
                    if (is_object($value) || is_array($value)) {
                        $item->unsetData($label);
                    }
                }
                $content .=  $item->convertToXml([], 'item', null, false);
            }
            $content .= "</items>&#xA;";
            $content .= "</response>";
            $xml = new \SimpleXMLElement($content);

            try {
                // Generate xml for each order
                $outputFile = fopen($outputDir . $fileName, "w");
                fwrite($outputFile, $xml->asXML());
                fclose($outputFile);
                $order->setLastIntegratedAt(date("Y-m-d H:i:s", $currentTime));
                $order->getResource()->saveAttribute($order, 'last_integrated_at');
                $this->logger->info("ExportOrder: " . $fileName . " created");
                // Generate xml for each order to archive folder
                $archiveFile = fopen($archiveDir . $fileName, "w");
                fwrite($archiveFile, $xml->asXML());
                fclose($archiveFile);
            } catch (\Exception $e) {
                $this->logger->debug($e->getMessage());
                throw $e;
            }
        }
    }

    public function getOrderCollection($orderStatus) {
        $orderModel = $this->orderFactory->create();
        $orderCollection = $orderModel->getCollection();
        $data = $orderCollection->addFieldToFilter('status', $orderStatus)->addFieldToFilter('store_id', $this->storeId)
            ->addFieldToFilter('last_integrated_at', array('null' => true));
        return $data;
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