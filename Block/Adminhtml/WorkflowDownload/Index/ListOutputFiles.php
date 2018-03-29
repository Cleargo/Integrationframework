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
    
    public function getFileList(){
        $var_location = $this->directoryList->getRoot();
        //Load workflow directory settings
        $workflow = $this->workflowScheduleFactory->create()->load(self::SCHEDULE_ID);
        $workflow->loadRelation();
        $relations = $workflow->getRelation();
        foreach ($relations as $rel) {
            $params = $rel['parameters'];
            $params = json_decode($params, true);
            $path_set = $var_location . $params['export_path'];
            $folder_content = scandir($path_set);
            $filelist = [];
            foreach ($folder_content as $content){
                //Only accept CSV file
                $file_path = $path_set . $content;
                $fileinfo = pathinfo($file_path);
                if (isset($fileinfo['extension']) && $fileinfo['extension'] == 'csv'){
                    $filelist[] = $content;
                }
            }
            return $filelist;
        }
    }
    
    public function processFilelist(){
        $result = [];
        $filelist = $this->getFileList();
        foreach ($filelist as $file){
            $filename_parts = explode("_", $file);
            $idx = 'paid';
            if ($filename_parts[3] == 'unpaid')
                $idx = 'unpaid';
            if (!isset($result[$filename_parts[2]][$idx]))
                $result[$filename_parts[2]][$idx] = [];
            array_push($result[$filename_parts[2]][$idx], $file);
        }
        return $result;
    }

    public function getStoreName($id){
        $website_id = $this->_storeManager->getStore($id)->getWebsiteId();
        return $this->_storeManager->getWebsite($website_id)->getName();
    }

    public function getFileLabel($filename){
        $filename_parts = explode("_", $filename);
        $idx = 3;
        if ($filename_parts[3] == 'unpaid')
            $idx += 1;
        $date = $filename_parts[$idx];
        $time = str_replace('.csv', '', $filename_parts[$idx + 1]);
        return __("Order Export on ") . date("Y-m-d", strtotime($date)) . " " . date("H:i:s", strtotime($time));
    }

    public function getDownloadLink(){
        return $this->getUrl('cleargo_integrationframeworks/workflowdownload/download');
    }

    public function getStartEndDate(){
        return [
            'start_date' => $this->_scopeConfig->getValue('product/event/latest_event_start_date'),
            'end_date' => $this->_scopeConfig->getValue('product/event/latest_event_end_date'),
        ];
    }
}