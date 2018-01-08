<?php


namespace Cleargo\Integrationframeworks\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface WorkflowPlansRepositoryInterface
{


    /**
     * Save WorkflowPlans
     * @param \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface $workflowPlans
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface $workflowPlans
    );

    /**
     * Retrieve WorkflowPlans
     * @param string $workflowplansId
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($workflowplansId);

    /**
     * Retrieve WorkflowPlans matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete WorkflowPlans
     * @param \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface $workflowPlans
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface $workflowPlans
    );

    /**
     * Delete WorkflowPlans by ID
     * @param string $workflowplansId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($workflowplansId);
}
