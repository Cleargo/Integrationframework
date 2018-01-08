<?php


namespace Cleargo\Integrationframeworks\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface WorkflowScheduleRepositoryInterface
{


    /**
     * Save WorkflowSchedule
     * @param \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface $workflowSchedule
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface $workflowSchedule
    );

    /**
     * Retrieve WorkflowSchedule
     * @param string $workflowscheduleId
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($workflowscheduleId);

    /**
     * Retrieve WorkflowSchedule matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete WorkflowSchedule
     * @param \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface $workflowSchedule
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface $workflowSchedule
    );

    /**
     * Delete WorkflowSchedule by ID
     * @param string $workflowscheduleId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($workflowscheduleId);
}
