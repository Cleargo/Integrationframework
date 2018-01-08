<?php


namespace Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowSchedule;

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
            'Cleargo\Integrationframeworks\Model\WorkflowSchedule',
            'Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowSchedule'
        );
    }
}
