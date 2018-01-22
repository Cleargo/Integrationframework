<?php

namespace Cleargo\Integrationframeworks\Model\Component;

use Cleargo\Integrationframeworks\Logger\Logger;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Sales\Model\OrderFactory;

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


    public function __construct(Logger $logger, OrderRepositoryInterface $orderRepository,
                                SearchCriteriaBuilder $searchCriteriaBuilder, FilterBuilder $filterBuilder,
                                DirectoryList $directoryList, OrderFactory $orderFactory)
    {
        $this->logger = $logger;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->directoryList = $directoryList;
        $this->orderFactory = $orderFactory;
    }

    public function execute() {
        var_dump('Export Order Start');

        // TODO: Export Order xml
        $this->exportOrderXml();

        // TODO: Update last_sync for Order

        var_dump('Export Order Executed');
    }

    public function exportOrderXml() {
        $orderStatus = $this->relationParams->order_status;
        $exportPath = $this->relationParams->export_path;
        $outputDir = $this->directoryList->getRoot() . $exportPath;

        // TODO: Get Order Collection
        $this->orderCollection = $this->getOrderCollection($orderStatus);

        if (!$this->orderCollection->count()) {
            $this->logger->info("ExportOrder: There are no Order for export");
        } else {
            $this->logger->info("ExportOrder: " . $this->orderCollection->count() . " order(s) processed");
        }

        // TODO: Export Order Xml
        foreach ($this->orderCollection as $order) {
            $currentTime = time();
            $fileTime = date("Ymd_HisS", $currentTime);
            $fileName = 'order_export_' . $order->getIncrementId() . '_' . $fileTime . '.xml';
            //var_dump($fileName);

            $content = "<response>&#xA;";
            $content .=  $order->toXml([], null, null, false);
            $content .= "</response>";
            $xml = new \SimpleXMLElement($content);

            // TODO: generate xml for each order
            $outputFile = fopen($outputDir . $fileName, "w");
            try {
                fwrite($outputFile, $xml->asXML());
                fclose($outputFile);
                $order->setNavLastSyncAt(date("Y-m-d H:i:s", $currentTime));
                $order->getResource()->saveAttribute($order, 'nav_last_sync_at');
                $this->logger->info("ExportOrder: " . $fileName . " created");
            } catch (Exception $e) {
                $this->logger->addDebug($e->getMessage());
            }
        }
    }

    public function getOrderCollection($orderStatus) {
        // TODO: Change criteria with nav_last_sync_at, website_id, store_id also after order attribute added

        if ($orderStatus == "confirmed and paid") {
            // Get Order by repository
            /*$searchCriteria = $this->searchCriteriaBuilder->addFilters(
                [
                    $this->filterBuilder->setField('status')
                        ->setValue('complete')
                        ->setConditionType('eq')
                        ->create(),
                ])->create();
            $data = $this->orderRepository->getList($searchCriteria);*/

            // Get Order by OrderFactory
            $orderModel = $this->orderFactory->create();
            $orderCollection = $orderModel->getCollection();
            $data = $orderCollection->addFieldToFilter('status', 'complete')->addFieldToFilter('store_id', $this->storeId)
                ->addFieldToFilter('nav_last_sync_at', array('null' => true));

        } else {
            // Get Order by repository
            /*$searchCriteria = $this->searchCriteriaBuilder->addFilter('status', 'complete', 'eq')->create();
            $data = $this->orderRepository->getList($searchCriteria);*/

            // Get Order by OrderFactory
            $orderModel = $this->orderFactory->create();
            $orderCollection = $orderModel->getCollection;
            $data = $orderCollection->addFieldToFilter('status', 'complete')->addFieldToFilter('store_id', $this->storeId)
                ->addFieldToFilter('nav_last_sync_at', array('null' => true));
        }
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