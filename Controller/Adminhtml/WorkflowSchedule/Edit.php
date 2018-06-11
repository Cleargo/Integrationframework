<?php


namespace Cleargo\Integrationframeworks\Controller\Adminhtml\WorkflowSchedule;

class Edit extends \Cleargo\Integrationframeworks\Controller\Adminhtml\WorkflowSchedule
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
        $id = $this->getRequest()->getParam('workflowschedule_id');
        $model = $this->_objectManager->create('Cleargo\Integrationframeworks\Model\WorkflowSchedule');
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Workflowschedule no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('cleargo_integrationframeworks_workflowschedule', $model);
        
        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Workflowschedule') : __('New Workflowschedule'),
            $id ? __('Edit Workflowschedule') : __('New Workflowschedule')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Workflowschedules'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getName() : __('New Workflowschedule'));
        return $resultPage;
    }
}
