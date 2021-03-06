<?php


namespace Cleargo\Integrationframeworks\Controller\Adminhtml\WorkflowComponentDefinitionParameters;

class Edit extends \Cleargo\Integrationframeworks\Controller\Adminhtml\WorkflowComponentDefinitionParameters
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
        $id = $this->getRequest()->getParam('workflowcomponentdefinitionparameters_id');
        $model = $this->_objectManager->create('Cleargo\Integrationframeworks\Model\WorkflowComponentDefinitionParameters');
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Workflowcomponentdefinitionparameters no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('cleargo_integrationframeworks_workflowcomponentdefinitionparameters', $model);
        
        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Workflowcomponentdefinitionparameters') : __('New Workflowcomponentdefinitionparameters'),
            $id ? __('Edit Workflowcomponentdefinitionparameters') : __('New Workflowcomponentdefinitionparameters')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Workflowcomponentdefinitionparameterss'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New Workflowcomponentdefinitionparameters'));
        return $resultPage;
    }
}
