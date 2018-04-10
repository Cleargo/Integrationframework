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
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Cleargo\PurchaseQuota\Helper\Data as PurchaseQuotaHelper;

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

    protected $fileFactory;

    protected $rawFactory;

    protected $purchaseQuotaHelper;

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
        CategoryRepositoryInterface $categoryRepositoryInterface,
        FileFactory $fileFactory,
        RawFactory $resultRawFactory,
        PurchaseQuotaHelper $PurchaseQuotaHelperData
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
        $this->fileFactory = $fileFactory;
        $this->rawFactory = $resultRawFactory;
        $this->purchaseQuotaHelper = $PurchaseQuotaHelperData;
    }

    public function execute() {
        var_dump("Export Order to Loreal Start");
        $this->exportOrderTxt();
        var_dump('Export Order to Loreal Executed');
    }

    public function exportOrderTxt() {
        $orderStatus = $this->relationParams->order_status;
        $exportPath = $this->relationParams->export_path;
        $outputDir = $this->directoryList->getRoot() . $exportPath;

        $this->orderCollection = $this->getOrderCollection($orderStatus, $this->storeId);

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
            $this->logger->info("ExportOrder: " . $this->orderCollection->count() . " order(s) processed");
        }
        try {
            // Export Order Xml
            $csv_header = [
                "",
                "Div",
                "Type",
                "Reason",
                "WHType",
                "PORef",
                "OrdDate",
                "DelDate",
                "InvDate",
                "SoldTo",
                "ShipTo",
                "MSICust",
                "Remark",
                "Material",
                "MSIMaterial",
                "OrderQty",
                "FreeQty",
                "Price",
                "GLN",
                "EDISupp"
            ];
            $csv_row = [];
            foreach ($this->orderCollection as $order) {
                $products = $order->getAllItems();
                foreach ($products as $product) {
                    //Get Product
                    $product_factory = $this->product->create()->load($product->getProductId());
                    //Map brand_code with sap_division_code
                    $brand_code = $this->getSelectOptionText($product_factory, 'brand_name');
                    $sap_division_code = $this->mapBrandCodeSapDivisionCode($brand_code);
                    //See if free goods checkout or staff purchase checkout
                    $type = $this->isFgStore($order->getStoreId()) ? "YFD" : "YOR";
                    //See if free goods checkout or staff purchase checkout
                    $wh_type = $this->isFgStore($order->getStoreId()) ? "LSFG" : "LSPO";
                    //Staff code
                    //Get Customer id
                    $customer_id = $order->getCustomerId();
                    $staff_no = '';
                    if ($customer_id != '') {
                        $customer = $this->customer->getById($customer_id);
                        if ($customer->getCustomAttribute('staff_no'))
                            $staff_no = $customer->getCustomAttribute('staff_no')->getValue();
                    }
                    //Order Date
                    $order_date = $this->formatDateTxt($order->getCreatedAt());
                    //Delivery Date
                    $delivery_date = $this->scopeConfig->getValue("product/event/latest_event_delivery_date");
                    $delivery_date = $this->formatDateTxt($delivery_date);
                    //Sold To, Free Goods TBC
                    $sold_to = $this->isFgStore($order->getStoreId()) ? $this->scopeConfig->getValue("product/event/fg_soldto_code") : $this->scopeConfig->getValue("product/event/sp_soldto_code");
                    $csv_row[] = [
                        "", //Empty separator
                        $sap_division_code, //sap_division_code
                        $type, //type
                        "H00", //Reason
                        $wh_type, //WHType
                        $staff_no, //PORef
                        $order_date, //OrdDate
                        $delivery_date, //DelDate
                        $delivery_date, //InvDate
                        $sold_to, //SoldTo
                        $staff_no, //ShipTo
                        "NA", //MSICust
                        "", //Remark
                        $this->trimSku($product->getSku()), //Material
                        "X", //MSIMaterial
                        (int)$product->getQtyOrdered(), //OrderQty
                        "0", //FreeQty
                        (int)$product->getPrice(), //Price
                        "", //GLN
                        "", //EDISupp
                    ];
                }
                $currentTime = time();
                $order->setNavLastSyncAt(date("Y-m-d H:i:s", $currentTime));
                $order->getResource()->saveAttribute($order, 'nav_last_sync_at');
            }
            $this->outputFiles($csv_header, $csv_row, $orderStatus, $outputDir);
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
            throw $e;
        }
    }

    public function getOrderCollection($orderStatus, $store_id) {
        $orderModel = $this->orderFactory->create();
        $orderCollection = $orderModel->getCollection();
        $data = $orderCollection;
        if ($orderStatus !== ""){
            $data->addFieldToFilter('status', ['in' => $orderStatus]);
        } else{
            $data->addFieldToFilter('status', ['in' => ['processing', 'complete']]);
        }
        if ($store_id !== ""){
            if ($store_id == 1)
                $store_ids = $this->purchaseQuotaHelper->getWebsiteStoreIds('sp');
            else
                $store_ids = $this->purchaseQuotaHelper->getWebsiteStoreIds('fg');
            $data->addFieldToFilter('store_id', ['in' => $store_ids]);
        }
        //TODO: Filter processed order enable, temp disable during testing
        /*$data->addFieldToFilter('nav_last_sync_at', ['null' => true]);
        $field_arr[] = 'nav_last_sync_at';
        $cond_arr[] = ['null' => true];*/
        return $data;
    }

    protected function outputFiles($header, $rows, $orderStatus, $outputDir){
        //Output file
        $unpaid_text = '';
        if ($orderStatus == 'pending')
            $unpaid_text = 'unpaid_';
        $currentTime = time();
        $fileTime = date("Ymd_His", $currentTime);
        $fileName = 'order_export_' . $this->storeId . "_" . $unpaid_text . $fileTime . '.csv';
        // Generate xml for each order
        $outputFile = fopen($outputDir . $fileName, "w");
        fputcsv($outputFile, $header, "|");
        foreach ($rows as $row)
            fputcsv($outputFile, $row, "|");
        fclose($outputFile);
        $this->logger->info("ExportOrder: " . $fileName . " created");
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
            if ($cat_name)
                return $cat_name[0];
        }
        return null;
    }

    protected function mapBrandCodeSapDivisionCode($brand_code){
        $map = [
            "" => "",
            "BIO" => "32",
            "GAB" => "3A",
            "GRN" => "17",
            "HR" => "33",
            "KIE" => "38",
            "LRP" => "41",
            "LAN" => "31",
            "LOP" => "17",
            "MTX" => "22",
            "MBL" => "17",
            "SHU" => "34",
            "SKC" => "42",
            "STL" => "3H",
            "VIC" => "40",
            "YSL" => "3E",
            "ZEG" => "3I",
            "GAX" => "3A",
            "GAY" => "3A",
            "GRX" => "17",
            "KIX" => "38",
            "LOZ" => "17",
            "LOY" => "17",
            "LOX" => "17",
            "LOW" => "17",
            "LOV" => "17",
            "LPX" => "20",
            "MBX" => "17",
            "RLX" => "37",
            "SLM" => "3H",
            "LPP" => "20",
            "HER" => "33",
            "SUA" => "34",
            "SKS" => "42",
            "DSL" => "3B",
            "KER" => "21",
            "MMM" => "3K",
            "VRF" => "3D",
            "PLY" => "20",
            "CLA" => "3L",
            "UD" => "3P",
            "AC" => "3Q",
            "R&G" => "3F",
        ];
        return $map[$brand_code];
    }

    protected function formatDateTxt($date){
        return date("Ymd", strtotime($date));
    }

    protected function isFgStore($order_id){
        return in_array($order_id, $this->purchaseQuotaHelper->getWebsiteStoreIds('fg'));
    }

    protected function trimSku($sku){
        $sku_parts = explode("-", $sku);
        array_pop($sku_parts);
        return implode("-", $sku_parts);
    }
}