<?php


namespace Cleargo\Integrationframeworks\Api\Data;

interface WorkflowPlansInterface
{

    const SCHEDULE_ID = 'schedule_id';
    const START_TIME = 'start_time';
    const RELATION_ID = 'relation_id';
    const EXECUTION_AT = 'execution_at';
    const STORE_ID = 'store_id';
    const END_TIME = 'end_time';
    const MESSAGE = 'message';
    const WEBSITE_ID = 'website_id';
    const WORKFLOWPLANS_ID = 'workflowplans_id';
    const STATUS = 'status';
    const PLAN_ID = 'plan_id';
    const SCHEDULE_NAME = 'schedule_name';


    /**
     * Get workflowplans_id
     * @return string|null
     */
    public function getWorkflowplansId();

    /**
     * Set workflowplans_id
     * @param string $workflowplans_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setWorkflowplansId($workflowplansId);

    /**
     * Get plan_id
     * @return string|null
     */
    public function getPlanId();

    /**
     * Set plan_id
     * @param string $plan_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setPlanId($plan_id);

    /**
     * Get schedule_id
     * @return string|null
     */
    public function getScheduleId();

    /**
     * Set schedule_id
     * @param string $schedule_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setScheduleId($schedule_id);

    /**
     * Get website_id
     * @return string|null
     */
    public function getWebsiteId();

    /**
     * Set website_id
     * @param string $website_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setWebsiteId($website_id);

    /**
     * Get store_id
     * @return string|null
     */
    public function getStoreId();

    /**
     * Set store_id
     * @param string $store_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setStoreId($store_id);

    /**
     * Get schedule_name
     * @return string|null
     */
    public function getScheduleName();

    /**
     * Set schedule_name
     * @param string $schedule_name
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setScheduleName($schedule_name);

    /**
     * Get relation_id
     * @return string|null
     */
    public function getRelationId();

    /**
     * Set relation_id
     * @param string $relation_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setRelationId($relation_id);

    /**
     * Get start_time
     * @return string|null
     */
    public function getStartTime();

    /**
     * Set start_time
     * @param string $start_time
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setStartTime($start_time);

    /**
     * Get execution_at
     * @return string|null
     */
    public function getExecutionAt();

    /**
     * Set execution_at
     * @param string $execution_at
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setExecutionAt($execution_at);

    /**
     * Get end_time
     * @return string|null
     */
    public function getEndTime();

    /**
     * Set end_time
     * @param string $end_time
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setEndTime($end_time);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setStatus($status);

    /**
     * Get message
     * @return string|null
     */
    public function getMessage();

    /**
     * Set message
     * @param string $message
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setMessage($message);
}
