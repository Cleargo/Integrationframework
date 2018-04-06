<?php
/**
 * Download
 *
 * @copyright Copyright © 2018 PY Yick. All rights reserved.
 * @author    py.yick@cleargo.com
 */

namespace Cleargo\Integrationframeworks\Controller\Adminhtml\WorkflowDownload;

class Download extends \Magento\Backend\App\Action
{
    protected $fileFactory;

    protected $rawFactory;

    protected $orderFactory;

    protected $logger;

    protected $customer;

    protected $product;

    protected $category;

    protected $scopeConfig;

    /**
     * Download constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\Filesystem\DirectoryList $directoryList
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepositoryInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
    ) {
        $this->fileFactory = $fileFactory;
        $this->rawFactory = $resultRawFactory;
        $this->orderFactory = $orderFactory;
        $this->logger = $logger;
        $this->customer = $customerRepositoryInterface;
        $this->product = $productFactory;
        $this->category = $categoryRepositoryInterface;
        $this->scopeConfig = $scopeConfigInterface;

        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $post_type = $this->getRequest()->getParam('type');
        $post_format = $this->getRequest()->getParam('format');
        //Separate case
        $criteria = $this->typeHandler($post_type);
        $this->logger->info(json_encode($criteria));
        $orders = $this->getOrderCollection($criteria['website_id'], $criteria['status']);
        $file_content = $this->processOrderCollection($orders, $post_format);
        $this->fileFactory->create(
            "output." . $post_format, //File name
            $file_content //Content
        );
        $resultRaw = $this->rawFactory->create();
        return $resultRaw;
    }

    protected function typeHandler($value){
        if ($value == 'all'){
            return [
                'website_id' => null,
                'status' => null
            ];
        } else {
            $value_arr = explode('-', $value);
            return [
                'website_id' => $value_arr[0],
                'status' => $value_arr[1] == 'paid' ? 'complete' : 'pending'
            ];
        }
    }

    protected function getOrderCollection($orderStatus = null, $store_id = null) {
        $orderModel = $this->orderFactory->create();
        $orderCollection = $orderModel->getCollection();
        $data = $orderCollection;
        $cond_arr = [];
        $field_arr = [];
        $this->logger->info($orderStatus);
        $this->logger->info($store_id);
        if ($orderStatus !== null){
            $field_arr[] = 'status';
            $cond_arr[] = ['in' => $orderStatus];
        } else{
            $field_arr[] = 'status';
            $cond_arr[] = ['in' => ['pending', 'complete']];
        }
        if ($store_id !== null){
            $field_arr[] = 'store_id';
            $cond_arr[] = ['in' => $store_id];
        }
        return $data->addFieldToFilter($field_arr, [$cond_arr]);
    }

    protected function processOrderCollection($orderCollection, $type){
        if (!$orderCollection->count()) {
            $this->logger->info("ExportOrder: There are no Order for export");
            return;
        } else {
            $csv_output = "";
            if ($type == 'csv') {
                // Export Order CSV
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
                $csv_output .= $this->outputCSV($csv_header);
                foreach ($orderCollection as $order) {
                    $customer_id = $order->getCustomerId();
                    //Customer
                    $loreal_entity_id = '';
                    $staff_no = '';
                    $name = '';
                    if ($customer_id != '') {
                        $customer = $this->customer->getById($customer_id);
                        if ($customer->getCustomAttribute('loreal_entity_id'))
                            $loreal_entity_id = $customer->getCustomAttribute('loreal_entity_id')->getValue();//Entity Code
                        if ($customer->getCustomAttribute('staff_no'))
                            $staff_no = $customer->getCustomAttribute('staff_no')->getValue();//Staff No
                        $name = $customer->getFirstname() . " " . $customer->getLastname();//Staff Name
                    }
                    //Ordered Items
                    $products = $order->getAllItems();
                    $csv_order = [
                        $this->scopeConfig->getValue('product/event/latest_event_name'),//Sales Period
                        date("d-m-Y", strtotime($order->getCreatedAt())),//Order Date
                        $order->getStoreId() == 2 ? "Free Good" : "Staff Purchase",//Order Type
                        $order->getStatus(),//Order Status
                        $loreal_entity_id,
                        $staff_no,
                        $name
                    ];
                    foreach ($products as $product) {
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
                        $csv_output .= $this->outputCSV($csv_record);
                    }
                }
            } else {
                // Export Order TXT
                $csv_header[] = [
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
                $csv_record = $csv_header;
                $row = [];
                foreach ($orderCollection as $order) {
                    $products = $order->getAllItems();
                    foreach ($products as $product) {
                        //Get Product
                        $product_factory = $this->product->create()->load($product->getProductId());
                        //Map brand_code with sap_division_code
                        $brand_code = $this->getSelectOptionText($product_factory, 'brand_name');
                        $sap_division_code = $this->mapBrandCodeSapDivisionCode($brand_code);
                        //See if free goods checkout or staff purchase checkout
                        $type = $order->getStoreId() == 2 ? "YFD" : "YOR";
                        //See if free goods checkout or staff purchase checkout
                        $wh_type = $order->getStoreId() == 2 ? "LSFG" : "LSPO";
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
                        $sold_to = $order->getStoreId() == 2 ? "TBC" : "120467";
                        $row[] = [
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
                            $product->getSku(), //Material
                            "X", //MSIMaterial
                            (int)$product->getQtyOrdered(), //OrderQty
                            "0", //FreeQty
                            (int)$product->getPrice(), //Price
                            "", //GLN
                            "", //EDISupp
                        ];
                        $csv_record = array_merge($csv_record, $row);
                    }
                }
                $this->logger->info(json_encode($csv_record));
                foreach ($csv_record as $line)
                    $csv_output .= $this->outputCSV($line, $type) . "\r\n";
            }
            return $csv_output;
        }
    }

    protected function outputCSV($data, $type = 'csv') {
        $csv = fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
        if ($type == 'csv')
            fputcsv($csv, $data);
        else
            fputcsv($csv, $data, "|");
        rewind($csv);
        // put it all in a variable
        return stream_get_contents($csv);
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
}