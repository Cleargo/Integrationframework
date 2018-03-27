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

    protected $logger;

    protected $directoryList;

    /**
     * Download constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Cleargo\Integrationframeworks\Model\WorkflowScheduleFactory $workflowScheduleFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Psr\Log\LoggerInterface $loggerInterface
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Cleargo\Integrationframeworks\Model\WorkflowScheduleFactory $workflowScheduleFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Psr\Log\LoggerInterface $loggerInterface,
        \Magento\Framework\Filesystem\DirectoryList $directoryList
    ) {
        $this->fileFactory = $fileFactory;
        $this->workflowScheduleFactory = $workflowScheduleFactory;
        $this->rawFactory = $resultRawFactory;
        $this->logger = $loggerInterface;
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
        $post = $this->getRequest()->getParams();
        //Set Filename
        $filename = "";
        foreach ($post as $key => $value){
            if ($value != '' && $key != 'form_key')
                $filename = $value;
        }
        if ($filename != "") {
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
            $file = $path_set . $filename;
            $file_content = file_get_contents($file);
            $this->logger->info($file);
            $this->logger->info($file_content);
            $this->fileFactory->create(
                $filename, //File name
                $file_content //Content
            );
            $resultRaw = $this->rawFactory->create();
            return $resultRaw;
        } else {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }
    }
}