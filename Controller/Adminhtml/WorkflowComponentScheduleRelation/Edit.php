<?php


namespace Cleargo\Integrationframeworks\Controller\Adminhtml\WorkflowComponentScheduleRelation;

class Edit extends \Cleargo\Integrationframeworks\Controller\Adminhtml\WorkflowComponentScheduleRelation
{

    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('workflowcomponentschedulerelation_id');
        $model = $this->_objectManager->create('Cleargo\Integrationframeworks\Model\WorkflowComponentScheduleRelation');
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Workflowcomponentschedulerelation no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('cleargo_integrationframeworks_workflowcomponentschedulerelation', $model);
        
        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Workflowcomponentschedulerelation') : __('New Workflowcomponentschedulerelation'),
            $id ? __('Edit Workflowcomponentschedulerelation') : __('New Workflowcomponentschedulerelation')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Workflowcomponentschedulerelations'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getId() : __('New Workflowcomponentschedulerelation'));
        return $resultPage;
    }
}
