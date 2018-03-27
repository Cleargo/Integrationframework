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
    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Cleargo\Integrationframeworks\Model\WorkflowScheduleFactory $workflowScheduleFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
    ) {
        $this->fileFactory = $fileFactory;
        $this->workflowScheduleFactory = $workflowScheduleFactory;
        $this->rawFactory = $resultRawFactory;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        //Set Filename
        $filename = "";
        foreach ($post as $value){
            if ($value !== NULL)
                $filename = $value;
        }
        if ($filename !== "") {
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
            $content = file_get_contents($file);
            $this->fileFactory->create(
                $filename, //File name
                $content //Content
            );
            $resultRaw = $this->rawFactory->create();
            return $resultRaw;
        } else {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }
    }
}