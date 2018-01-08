<?php


namespace Cleargo\Integrationframeworks\Api\Data;

interface WorkflowScheduleInterface
{

    const SCHEDULE_ID = 'schedule_id';
    const EXECUTION_INTERVAL = 'execution_interval';
    const SORT_ORDER = 'sort_order';
    const EXECUTION_ACTIVE_FROM = 'execution_active_from';
    const FILE_LOG_LEVEL = 'file_log_level';
    const ENABLE = 'enable';
    const NOTIFICATION_EMAIL = 'notification_email';
    const DESCRIPTION = 'description';
    const STORE_ID = 'store_id';
    const WORKFLOWSCHEDULE_ID = 'workflowschedule_id';
    const NAME = 'name';
    const WEBSITE_ID = 'website_id';
    const EXECUTION_TYPE = 'execution_type';
    const SCHEDULE_TYPE = 'schedule_type';


    /**
     * Get workflowschedule_id
     * @return string|null
     */
    public function getWorkflowscheduleId();

    /**
     * Set workflowschedule_id
     * @param string $workflowschedule_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setWorkflowscheduleId($workflowscheduleId);

    /**
     * Get schedule_id
     * @return string|null
     */
    public function getScheduleId();

    /**
     * Set schedule_id
     * @param string $schedule_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setScheduleId($schedule_id);

    /**
     * Get enable
     * @return string|null
     */
    public function getEnable();

    /**
     * Set enable
     * @param string $enable
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setEnable($enable);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setName($name);

    /**
     * Get description
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     * @param string $description
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setDescription($description);

    /**
     * Get website_id
     * @return string|null
     */
    public function getWebsiteId();

    /**
     * Set website_id
     * @param string $website_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
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
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setStoreId($store_id);

    /**
     * Get notification_email
     * @return string|null
     */
    public function getNotificationEmail();

    /**
     * Set notification_email
     * @param string $notification_email
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setNotificationEmail($notification_email);

    /**
     * Get execution_active_from
     * @return string|null
     */
    public function getExecutionActiveFrom();

    /**
     * Set execution_active_from
     * @param string $execution_active_from
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setExecutionActiveFrom($execution_active_from);

    /**
     * Get execution_interval
     * @return string|null
     */
    public function getExecutionInterval();

    /**
     * Set execution_interval
     * @param string $execution_interval
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setExecutionInterval($execution_interval);

    /**
     * Get execution_type
     * @return string|null
     */
    public function getExecutionType();

    /**
     * Set execution_type
     * @param string $execution_type
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setExecutionType($execution_type);

    /**
     * Get file_log_level
     * @return string|null
     */
    public function getFileLogLevel();

    /**
     * Set file_log_level
     * @param string $file_log_level
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setFileLogLevel($file_log_level);

    /**
     * Get schedule_type
     * @return string|null
     */
    public function getScheduleType();

    /**
     * Set schedule_type
     * @param string $schedule_type
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setScheduleType($schedule_type);

    /**
     * Get sort_order
     * @return string|null
     */
    public function getSortOrder();

    /**
     * Set sort_order
     * @param string $sort_order
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setSortOrder($sort_order);
}
