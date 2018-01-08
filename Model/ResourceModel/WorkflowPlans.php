<?php


namespace Cleargo\Integrationframeworks\Model\ResourceModel;

class WorkflowPlans extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('cleargo_integrationframeworks_workflowplans', 'workflowplans_id');
    }
}
