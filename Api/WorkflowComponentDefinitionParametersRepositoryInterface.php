<?php


namespace Cleargo\Integrationframeworks\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface WorkflowComponentDefinitionParametersRepositoryInterface
{


    /**
     * Save WorkflowComponentDefinitionParameters
     * @param \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface $workflowComponentDefinitionParameters
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface $workflowComponentDefinitionParameters
    );

    /**
     * Retrieve WorkflowComponentDefinitionParameters
     * @param string $workflowcomponentdefinitionparametersId
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($workflowcomponentdefinitionparametersId);

    /**
     * Retrieve WorkflowComponentDefinitionParameters matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete WorkflowComponentDefinitionParameters
     * @param \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface $workflowComponentDefinitionParameters
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface $workflowComponentDefinitionParameters
    );

    /**
     * Delete WorkflowComponentDefinitionParameters by ID
     * @param string $workflowcomponentdefinitionparametersId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($workflowcomponentdefinitionparametersId);
}
