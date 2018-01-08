<?php


namespace Cleargo\Integrationframeworks\Controller\Adminhtml\WorkflowSchedule;

class Delete extends \Cleargo\Integrationframeworks\Controller\Adminhtml\WorkflowSchedule
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
        $id = $this->getRequest()->getParam('workflowschedule_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('Cleargo\Integrationframeworks\Model\WorkflowSchedule');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Workflowschedule.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['workflowschedule_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Workflowschedule to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
