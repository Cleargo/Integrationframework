<?php


namespace Cleargo\Integrationframeworks\Model\ResourceModel;

class WorkflowComponentDefinition extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('workflow_component_definition', 'workflowcomponentdefinition_id');
    }
}
