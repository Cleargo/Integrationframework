<?php


namespace Cleargo\Integrationframeworks\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface WorkflowComponentScheduleRelationRepositoryInterface
{


    /**
     * Save WorkflowComponentScheduleRelation
     * @param \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface $workflowComponentScheduleRelation
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface $workflowComponentScheduleRelation
    );

    /**
     * Retrieve WorkflowComponentScheduleRelation
     * @param string $workflowcomponentschedulerelationId
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($workflowcomponentschedulerelationId);

    /**
     * Retrieve WorkflowComponentScheduleRelation matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete WorkflowComponentScheduleRelation
     * @param \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface $workflowComponentScheduleRelation
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface $workflowComponentScheduleRelation
    );

    /**
     * Delete WorkflowComponentScheduleRelation by ID
     * @param string $workflowcomponentschedulerelationId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($workflowcomponentschedulerelationId);
}
