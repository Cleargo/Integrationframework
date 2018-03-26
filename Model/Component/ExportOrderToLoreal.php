<?php

namespace Cleargo\Integrationframeworks\Model\Component;

use Cleargo\Integrationframeworks\Logger\Logger;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Sales\Model\OrderFactory;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;

class ExportOrderToLoreal
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

    protected $scopeConfig;

    protected $customer;

    protected $product;

    protected $category;

    public function __construct(
        Logger $logger,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        DirectoryList $directoryList,
        OrderFactory $orderFactory,
        File $io,
        ScopeConfigInterface $scopeConfigInterface,
        CustomerRepositoryInterface $customerRepositoryInterface,
        ProductFactory $productFactory,
        CategoryRepositoryInterface $categoryRepositoryInterface
    )
    {
        $this->logger = $logger;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->directoryList = $directoryList;
        $this->orderFactory = $orderFactory;
        $this->io = $io;
        $this->scopeConfig = $scopeConfigInterface;
        $this->customer = $customerRepositoryInterface;
        $this->product = $productFactory;
        $this->category = $categoryRepositoryInterface;
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
        try {
            //Output file
            $currentTime = time();
            $fileTime = date("Ymd_HisS", $currentTime);
            $fileName = 'order_export_' . $fileTime . '.csv';
            // Generate xml for each order
            $outputFile = fopen($outputDir . $fileName, "w");
            $archiveFile = fopen($archiveDir . $fileName, "w");
            // Export Order Xml
            $csv_record = [];
            $csv_order = [];
            $csv_order_item = [];
            $csv_header = [
                "Sales Period",
                "Order Date",
                "Order Type",
                "Order Status",
                "Entity Code",
                "Staff No",
                "Staff Name",
                "Item Code",
                "Brand",
                "Brand Name",
                "Division",
                "Category",
                "Unit Price",
                "Order Qty",
                "Provision Qty",
                "Received Qty",
                "Description"
            ];
            fputcsv($outputFile, $csv_header);
            fputcsv($archiveFile, $csv_header);
            foreach ($this->orderCollection as $order) {
                //Customer
                $customer = $this->customer->getById($order->getCustomerId());
                //Ordered Items
                $products = $order->getAllItems();
                $csv_order = [
                    $this->scopeConfig->getValue('product/event/latest_event_name'),//Sales Period
                    date("d-m-Y", strtotime($order->getCreatedAt())),//Order Date
                    $order->getStoreId() == 2 ? "Free Good" : "Staff Purchase",//Order Type
                    $order->getStatus(),//Order Status
                    $customer->getCustomAttribute('loreal_entity_id')->getValue(),//Entity Code
                    $customer->getCustomAttribute('staff_no')->getValue(),//Staff No
                    $customer->getFirstname() . " " . $customer->getLastname(),//Staff Name
                ];
                foreach ($products as $product){
                    //Get Product
                    $product_factory = $this->product->create()->load($product->getProductId());
                    $csv_order_item = [
                        $product->getSku(),//Item Code
                        $this->getSelectOptionText($product_factory, 'brand_name'),//Brand Name
                        $this->getSelectOptionText($product_factory, 'brand'),//Brand
                        $this->getSelectOptionText($product_factory, 'division'),//Division
                        $this->getProductCategory($product_factory),//Category
                        $product->getPrice(),//Unit Price
                        $product->getQtyOrdered(),//Order Qty
                        $product->getQtyInvoiced(),//Provision Qty
                        $product->getQtyShipped(),//Received Qty
                        $product->getName()//Description
                    ];
                    $csv_record = array_merge($csv_order, $csv_order_item);
                    fputcsv($outputFile, $csv_record);
                    fputcsv($archiveFile, $csv_record);
                }
                $currentTime = time();
                $order->setNavLastSyncAt(date("Y-m-d H:i:s", $currentTime));
                $order->getResource()->saveAttribute($order, 'nav_last_sync_at');
            }
            fclose($outputFile);
            fclose($archiveFile);
            $this->logger->info("ExportOrder: " . $fileName . " created");
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
            throw $e;
        }
    }

    public function getOrderCollection($orderStatus) {
        $orderModel = $this->orderFactory->create();
        $orderCollection = $orderModel->getCollection();
        $data = $orderCollection
            ->addFieldToFilter('status', $orderStatus)
            ->addFieldToFilter('store_id', $this->storeId);
            //->addFieldToFilter('nav_last_sync_at', array('null' => true));
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

    protected function getSelectOptionText($product, $code){
        $optionId = $product->getDataByKey($code);
        $optionText = null;
        $attributes = $product->getAttributes();
        if ($optionId && array_key_exists($code, $attributes)) {
            $attr = $attributes[$code];
            if ($attr->usesSource()) {
                $optionText = $attr->getSource()->getOptionText($optionId);
            }
        }
        return $optionText;
    }

    protected function getProductCategory($product){
        $cat_name = [];
        if ($categoryIds = $product->getCustomAttribute('category_ids')) {
            foreach ($categoryIds->getValue() as $categoryId) {
                $category = $this->category->get($categoryId);
                $cat_name[] = $category->getName();
            }
        }
        return $cat_name[0];
    }
}