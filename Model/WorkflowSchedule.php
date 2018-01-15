<?php


namespace Cleargo\Integrationframeworks\Model;

use Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface;

class WorkflowSchedule extends \Magento\Framework\Model\AbstractModel implements WorkflowScheduleInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowSchedule');
    }

    /**
     * Get workflowschedule_id
     * @return string
     */
    public function getWorkflowscheduleId()
    {
        return $this->getData(self::WORKFLOWSCHEDULE_ID);
    }

    /**
     * Set workflowschedule_id
     * @param string $workflowscheduleId
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setWorkflowscheduleId($workflowscheduleId)
    {
        return $this->setData(self::WORKFLOWSCHEDULE_ID, $workflowscheduleId);
    }

    /**
     * Get schedule_id
     * @return string
     */
    public function getScheduleId()
    {
        return $this->getData(self::SCHEDULE_ID);
    }

    /**
     * Set schedule_id
     * @param string $schedule_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setScheduleId($schedule_id)
    {
        return $this->setData(self::SCHEDULE_ID, $schedule_id);
    }

    /**
     * Get enable
     * @return string
     */
    public function getEnable()
    {
        return $this->getData(self::ENABLE);
    }

    /**
     * Set enable
     * @param string $enable
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setEnable($enable)
    {
        return $this->setData(self::ENABLE, $enable);
    }

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Set name
     * @param string $name
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get description
     * @return string
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * Set description
     * @param string $description
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Get website_id
     * @return string
     */
    public function getWebsiteId()
    {
        return $this->getData(self::WEBSITE_ID);
    }

    /**
     * Set website_id
     * @param string $website_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setWebsiteId($website_id)
    {
        return $this->setData(self::WEBSITE_ID, $website_id);
    }

    /**
     * Get store_id
     * @return string
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * Set store_id
     * @param string $store_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setStoreId($store_id)
    {
        return $this->setData(self::STORE_ID, $store_id);
    }

    /**
     * Get notification_email
     * @return string
     */
    public function getNotificationEmail()
    {
        return $this->getData(self::NOTIFICATION_EMAIL);
    }

    /**
     * Set notification_email
     * @param string $notification_email
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setNotificationEmail($notification_email)
    {
        return $this->setData(self::NOTIFICATION_EMAIL, $notification_email);
    }

    /**
     * Get execution_active_from
     * @return string
     */
    public function getExecutionActiveFrom()
    {
        return $this->getData(self::EXECUTION_ACTIVE_FROM);
    }

    /**
     * Set execution_active_from
     * @param string $execution_active_from
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setExecutionActiveFrom($execution_active_from)
    {
        return $this->setData(self::EXECUTION_ACTIVE_FROM, $execution_active_from);
    }

    /**
     * Get execution_interval
     * @return string
     */
    public function getExecutionInterval()
    {
        return $this->getData(self::EXECUTION_INTERVAL);
    }

    /**
     * Set execution_interval
     * @param string $execution_interval
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setExecutionInterval($execution_interval)
    {
        return $this->setData(self::EXECUTION_INTERVAL, $execution_interval);
    }

    /**
     * Get execution_type
     * @return string
     */
    public function getExecutionType()
    {
        return $this->getData(self::EXECUTION_TYPE);
    }

    /**
     * Set execution_type
     * @param string $execution_type
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setExecutionType($execution_type)
    {
        return $this->setData(self::EXECUTION_TYPE, $execution_type);
    }

    /**
     * Get file_log_level
     * @return string
     */
    public function getFileLogLevel()
    {
        return $this->getData(self::FILE_LOG_LEVEL);
    }

    /**
     * Set file_log_level
     * @param string $file_log_level
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setFileLogLevel($file_log_level)
    {
        return $this->setData(self::FILE_LOG_LEVEL, $file_log_level);
    }

    /**
     * Get schedule_type
     * @return string
     */
    public function getScheduleType()
    {
        return $this->getData(self::SCHEDULE_TYPE);
    }

    /**
     * Set schedule_type
     * @param string $schedule_type
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setScheduleType($schedule_type)
    {
        return $this->setData(self::SCHEDULE_TYPE, $schedule_type);
    }

    /**
     * Get sort_order
     * @return string
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * Set sort_order
     * @param string $sort_order
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowScheduleInterface
     */
    public function setSortOrder($sort_order)
    {
        return $this->setData(self::SORT_ORDER, $sort_order);
    }


    public function loadRelation(){

        $this->_getResource()->loadRelation($this);

        return $this;
    }
}
