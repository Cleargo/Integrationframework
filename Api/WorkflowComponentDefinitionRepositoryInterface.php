<?php


namespace Cleargo\Integrationframeworks\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface WorkflowComponentDefinitionRepositoryInterface
{


    /**
     * Save WorkflowComponentDefinition
     * @param \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface $workflowComponentDefinition
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface $workflowComponentDefinition
    );

    /**
     * Retrieve WorkflowComponentDefinition
     * @param string $workflowcomponentdefinitionId
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($workflowcomponentdefinitionId);

    /**
     * Retrieve WorkflowComponentDefinition matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete WorkflowComponentDefinition
     * @param \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface $workflowComponentDefinition
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface $workflowComponentDefinition
    );

    /**
     * Delete WorkflowComponentDefinition by ID
     * @param string $workflowcomponentdefinitionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($workflowcomponentdefinitionId);
}
