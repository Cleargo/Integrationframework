<?php


namespace Cleargo\Integrationframeworks\Controller\Adminhtml\WorkflowComponentScheduleRelation;

class Delete extends \Cleargo\Integrationframeworks\Controller\Adminhtml\WorkflowComponentScheduleRelation
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
        $id = $this->getRequest()->getParam('workflowcomponentschedulerelation_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('Cleargo\Integrationframeworks\Model\WorkflowComponentScheduleRelation');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Workflowcomponentschedulerelation.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['workflowcomponentschedulerelation_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Workflowcomponentschedulerelation to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
