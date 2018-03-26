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

        // Export Order Xml
        $csv = "";
        $csv_order = "";
        $csv .= "Sales Period,Order Date,Order Type,Order Status,Entity Code,Staff No,Staff Name,Team Code,Item Code,Brand Name,Division,Category,Unit Price,Order Qty,Provision Qty,Received Qty,Description";
        $csv .= "\r\n";
        foreach ($this->orderCollection as $order) {
            //Sales Period
            $csv_order .= $this->scopeConfig->getValue('product/event/latest_event_name') . ",";
            //Order Date
            $csv_order .= date("d-m-y", strtotime($order->getCreatedAt())) . ",";
            //Order Type
            $csv_order .= $order->getStoreId() == 2 ? "Free Good," : "Staff Purchase,";
            //Order Status
            $csv_order .= $order->getStatus() . ",";
            //Entity Code
            $customer = $this->customer->getById($order->getCustomerId());
            $csv_order .= $customer->getCustomAttribute('loreal_entity_id')->getValue() . ",";
            //Staff No
            $csv_order .= $customer->getCustomAttribute('staff_no')->getValue() . ",";
            //Staff Name
            $csv_order .= $customer->getFirstname() . " " . $customer->getLastname() . ",";
            //Team Code
            $csv_order .= $customer->getCustomAttribute('team_dept_id')->getValue() . ",";
            //ordered Items
            $products = $order->getAllItems();
            foreach ($products as $product){
                $csv .= $csv_order;
                $product_factory = $this->product->create()->load($product->getProductId());
                //Item Code
                $csv .= $product->getSku() . ",";
                //Brand Name
                $csv .= $this->getSelectOptionText($product_factory, 'brand_name') . ",";
                //Brand
                $csv .= $this->getSelectOptionText($product_factory, 'brand') . ",";
                //Division
                $csv .= $this->getSelectOptionText($product_factory, 'division') . ",";
                //Category
                $csv .= $this->getProductCategory($product_factory) . ",";
                //Unit Price
                $csv .= $product->getPrice() . ",";
                //Order Qty
                $csv .= $product->getQtyOrdered() . ",";
                //Provision Qty
                $csv .= $product->getQtyInvoiced() . ",";
                //Received Qty
                $csv .= $product->getQtyShipped() . ",";
                //Description
                $csv .= $product->getName();
                $csv .= "\r\n";
            }
            $csv .= "\r\n";
            $currentTime = time();
            $order->setNavLastSyncAt(date("Y-m-d H:i:s", $currentTime));
            $order->getResource()->saveAttribute($order, 'nav_last_sync_at');
        }
        //Output file
        $currentTime = time();
        $fileTime = date("Ymd_HisS", $currentTime);
        $fileName = 'order_export_' . $fileTime . '.csv';
        //var_dump($fileName);
        try {
            // Generate xml for each order
            $outputFile = fopen($outputDir . $fileName, "w");
            fwrite($outputFile, $csv);
            fclose($outputFile);
            // Generate xml for each order to archive folder
            $archiveFile = fopen($archiveDir . $fileName, "w");
            fwrite($archiveFile, $csv);
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
        $data = $orderCollection;
            //->addFieldToFilter('status', $orderStatus)
            //->addFieldToFilter('store_id', $this->storeId);
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
        return end($cat_name);
    }
}