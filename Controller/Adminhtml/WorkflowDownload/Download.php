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
            // Export Order Xml
            $csv_output = "";
            if ($type == 'csv') {
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
                    $this->logger->info($order->getIncrementId());
                    $this->logger->info($order->getCustomerId());
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
                $csv_output .= $this->outputCSV($csv_header, $type);
                return $csv_output;
                foreach ($orderCollection as $order) {
                    $this->logger->info($order->getIncrementId());
                    $this->logger->info($order->getCustomerId());
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
}