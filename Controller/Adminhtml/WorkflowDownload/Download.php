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
    const SCHEDULE_ID = 5;

    protected $fileFactory;

    protected $workflowScheduleFactory;

    protected $rawFactory;

    protected $directoryList;

    /**
     * Download constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Cleargo\Integrationframeworks\Model\WorkflowScheduleFactory $workflowScheduleFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Cleargo\Integrationframeworks\Model\WorkflowScheduleFactory $workflowScheduleFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\Filesystem\DirectoryList $directoryList
    ) {
        $this->fileFactory = $fileFactory;
        $this->workflowScheduleFactory = $workflowScheduleFactory;
        $this->rawFactory = $resultRawFactory;
        $this->directoryList = $directoryList;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $post_merge_file = $this->getRequest()->getParam('file-list');
        //Set file path
        $var_location = $this->directoryList->getRoot();
        //Load workflow directory settings
        $workflow = $this->workflowScheduleFactory->create()->load(self::SCHEDULE_ID);
        $workflow->loadRelation();
        $relations = $workflow->getRelation();
        foreach ($relations as $rel) {
            $params = $rel['parameters'];
            $params = json_decode($params, true);
            $path_set = $var_location . $params['export_path'];
        }
        $output = [];
        $first_file = TRUE;
        foreach ($post_merge_file as $file){
            $file = $path_set . $file;
            if (($handle = fopen($file, "r")) !== FALSE) {
                $row = 1;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    if ($row > 1 || $first_file){
                        $output[] = $data;
                    }
                    $row++;
                    $first_file = FALSE;
                }
                fclose($handle);
            }
        }
        $file_content = "";
        foreach($output as $arr) {
            $file_content .= implode(",", $arr) . "\r\n";
        }
        $this->fileFactory->create(
            "output.csv", //File name
            $file_content //Content
        );
        $resultRaw = $this->rawFactory->create();
        return $resultRaw;
    }
}