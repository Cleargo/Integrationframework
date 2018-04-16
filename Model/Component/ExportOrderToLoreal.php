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
                    //Resaon
                    $reason = $this->isFgStore($order->getStoreId()) ? "H00" : "";
                    //See if free goods checkout or staff purchase checkout
                    $wh_type = $this->isFgStore($order->getStoreId()) ? "LSFG" : "LSPO";
                    //Staff code + ShipTo
                    //Get Customer id
                    $customer_id = $order->getCustomerId();
                    $staff_no = '';
                    $ship_to = '';
                    $team_dept_desc = '';
                    if ($customer_id != '') {
                        $customer = $this->customer->getById($customer_id);
                        if ($customer->getCustomAttribute('staff_no'))
                            $staff_no = $customer->getCustomAttribute('staff_no')->getValue();
                        if ($customer->getCustomAttribute('ship_to'))
                            $ship_to = $customer->getCustomAttribute('ship_to')->getValue();
                        if ($customer->getCustomAttribute('team_dept_desc'))
                            $team_dept_desc = $customer->getCustomAttribute('team_dept_desc')->getValue();
                    }
                    //Order Date
                    $order_date = $this->formatDateTxt($order->getCreatedAt());
                    //Delivery Date
                    $delivery_date = $this->scopeConfig->getValue("product/event/latest_event_delivery_date");
                    $delivery_date = $this->formatDateTxt($delivery_date);
                    //Sold To
                    $sold_to = $this->isFgStore($order->getStoreId()) ? $this->scopeConfig->getValue("product/event/fg_soldto_code") : $this->scopeConfig->getValue("product/event/sp_soldto_code");
                    //Price
                    $price = $this->isFgStore($order->getStoreId()) ? 0 : $product->getPrice() - $product->getDiscountAmount();
                    $csv_row = [
                        "", //Empty separator
                        $sap_division_code, //sap_division_code
                        $type, //type
                        $reason, //Reason
                        $wh_type, //WHType
                        $staff_no, //PORef
                        $order_date, //OrdDate
                        $delivery_date, //DelDate
                        $delivery_date, //InvDate
                        $sold_to, //SoldTo
                        $ship_to, //ShipTo
                        "NA", //MSICust
                        $team_dept_desc, //Remark
                        $this->trimSku($product->getSku()), //Material
                        "X", //MSIMaterial
                        (int)$product->getQtyOrdered(), //OrderQty
                        "0", //FreeQty
                        $price, //Price
                        "", //GLN
                        "", //EDISupp
                    ];
                    $this->outputFiles($csv_header, $csv_row, $outputDir, $staff_no, $order_date);
                }
                $currentTime = time();
                $order->setNavLastSyncAt(date("Y-m-d H:i:s", $currentTime));
                $order->getResource()->saveAttribute($order, 'nav_last_sync_at');
            }
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
            //No classification on order status for SAP
            $data->addFieldToFilter('status', ['in' => ['processing', 'complete']]);
        }
        if ($store_id !== ""){
            if ($store_id == 1)
                $store_ids = $this->purchaseQuotaHelper->getWebsiteStoreIds('sp');
            else
                $store_ids = $this->purchaseQuotaHelper->getWebsiteStoreIds('fg');
            $data->addFieldToFilter('store_id', ['in' => $store_ids]);
        }
        //Filter processed order
        $data->addFieldToFilter('nav_last_sync_at', ['null' => true]);
        return $data;
    }

    protected function outputFiles($header, $row, $outputDir, $customer_id, $order_date){
        //Output file
        //Store
        $store_label = $this->isFgStore($this->storeId) ? "FG" : "SP";
        //Staff no
        if ($customer_id != "")
            $customer_text = $customer_id . '_';
        else
            $customer_text = 'null_';
        //Timestamp
        $fileTime = $order_date . "_";
        //Final Filename
        $fileName = $fileTime . $customer_text . $store_label . '.dat';
        // Generate DAT for each order
        $need_header = TRUE;
        if (file_exists($outputDir . $fileName)){
            $need_header = FALSE;
        }
        $outputFile = fopen($outputDir . $fileName, "a+");
        if ($need_header)
            fputcsv($outputFile, $header, "|");
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

    protected function isFgStore($store_id){
        return in_array($store_id, $this->purchaseQuotaHelper->getWebsiteStoreIds('fg'));
    }

    protected function trimSku($sku){
        $sku_parts = explode("-", $sku);
        array_pop($sku_parts);
        return implode("-", $sku_parts);
    }
}