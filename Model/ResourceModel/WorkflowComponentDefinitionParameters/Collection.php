<?php


namespace Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowComponentDefinitionParameters;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Cleargo\Integrationframeworks\Model\WorkflowComponentDefinitionParameters',
            'Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowComponentDefinitionParameters'
        );
    }
}
