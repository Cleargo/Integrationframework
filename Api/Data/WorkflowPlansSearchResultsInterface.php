<?php


namespace Cleargo\Integrationframeworks\Api\Data;

interface WorkflowPlansSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get WorkflowPlans list.
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface[]
     */
    public function getItems();

    /**
     * Set plan_id list.
     * @param \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
