<?php
/**
 * List1
 *
 * @copyright Copyright © 2018 PY Yick. All rights reserved.
 * @author    py.yick@cleargo.com
 */

namespace Cleargo\Integrationframeworks\Block\Adminhtml\WorkflowDownload\Index;

class ListOutputFiles extends \Magento\Framework\View\Element\Template
{
    const SCHEDULE_ID = 5;
    
    protected $directoryList;

    protected $workflowScheduleFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Cleargo\Integrationframeworks\Model\WorkflowScheduleFactory $workflowScheduleFactory,
        array $data
    )
    {
        parent::__construct($context, $data);
        $this->directoryList = $directoryList;
        $this->workflowScheduleFactory = $workflowScheduleFactory;
    }

    public function execute()
    {
        return  $resultPage = $this->resultPageFactory->create();
    }

    public function processFilelist(){
        $result = [];
        $store_list = $this->_storeManager->getGroups();
        foreach ($store_list as $store){
            $result[$store->getId()] = [
                "paid",
                "unpaid"
            ];
        }
        return $result;
    }

    public function getStoreName($id){
        $website_id = $this->_storeManager->getStore($id)->getWebsiteId();
        return $this->_storeManager->getWebsite($website_id)->getName();
    }

    public function getStartEndDate(){
        return [
            'start_date' => $this->_scopeConfig->getValue('product/event/latest_event_start_date'),
            'end_date' => $this->_scopeConfig->getValue('product/event/latest_event_end_date'),
        ];
    }

    public function getDownloadLink(){
        return $this->getUrl('cleargo_integrationframeworks/workflowdownload/download');
    }
}