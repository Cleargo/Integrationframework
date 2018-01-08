<?php


namespace Cleargo\Integrationframeworks\Controller\Adminhtml\WorkflowPlans;

class Delete extends \Cleargo\Integrationframeworks\Controller\Adminhtml\WorkflowPlans
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('workflowplans_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('Cleargo\Integrationframeworks\Model\WorkflowPlans');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Workflowplans.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['workflowplans_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Workflowplans to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
