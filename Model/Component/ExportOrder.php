<?php

namespace Cleargo\Integrationframeworks\Model\Component;

use Psr\Log\LoggerInterface;
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

    protected $directoryList;
    
    protected $orderFactory;


    public function __construct(LoggerInterface $logger, OrderRepositoryInterface $orderRepository,
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
        // TODO: Export Order xml
        $this->exportOrderXml();

        // TODO: Update last_sync for Order

        var_dump('ExportOrder executed');
    }

    public function exportOrderXml() {
        $orderStatus = $this->relationParams->order_status;
        $exportPath = $this->relationParams->export_path;
        $outputDir = $this->directoryList->getRoot() . $exportPath;

        // TODO: Get Order Collection
        $this->orderCollection = $this->getOrderCollection($orderStatus);

        // TODO: Export Order Xml
        foreach ($this->orderCollection as $order) {
            $currentTime = date("Ymd_HisS", time());
            $fileName = 'order_export_' . $order->getIncrementId() . '_' . $currentTime . '.xml';
            //var_dump($fileName);

            $content =  $order->toXml();

            // TODO: generate xml for each order
            $outputFile = fopen($outputDir . $fileName, "w");
            try {
                fwrite($outputFile, $content);
                fclose($outputFile);
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

}