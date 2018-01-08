<?php


namespace Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowPlans;

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
            'Cleargo\Integrationframeworks\Model\WorkflowPlans',
            'Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowPlans'
        );
    }
}
