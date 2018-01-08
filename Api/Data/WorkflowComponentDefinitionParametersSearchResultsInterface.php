<?php


namespace Cleargo\Integrationframeworks\Api\Data;

interface WorkflowComponentDefinitionParametersSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get WorkflowComponentDefinitionParameters list.
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface[]
     */
    public function getItems();

    /**
     * Set component_param_id list.
     * @param \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
