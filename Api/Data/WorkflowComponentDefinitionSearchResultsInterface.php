<?php


namespace Cleargo\Integrationframeworks\Api\Data;

interface WorkflowComponentDefinitionSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get WorkflowComponentDefinition list.
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface[]
     */
    public function getItems();

    /**
     * Set component_id list.
     * @param \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
