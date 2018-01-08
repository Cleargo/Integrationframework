<?php


namespace Cleargo\Integrationframeworks\Api\Data;

interface WorkflowComponentScheduleRelationSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get WorkflowComponentScheduleRelation list.
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface[]
     */
    public function getItems();

    /**
     * Set relation_id list.
     * @param \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
