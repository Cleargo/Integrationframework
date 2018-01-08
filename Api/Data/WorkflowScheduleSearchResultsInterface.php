<?php


namespace Cleargo\Integrationframeworks\Api\Data;

interface WorkflowScheduleSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get WorkflowSchedule list.
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface[]
     */
    public function getItems();

    /**
     * Set schedule_id list.
     * @param \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
