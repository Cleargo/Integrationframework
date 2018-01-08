<?php


namespace Cleargo\Integrationframeworks\Model;

use Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface;

class WorkflowPlans extends \Magento\Framework\Model\AbstractModel implements WorkflowPlansInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowPlans');
    }

    /**
     * Get workflowplans_id
     * @return string
     */
    public function getWorkflowplansId()
    {
        return $this->getData(self::WORKFLOWPLANS_ID);
    }

    /**
     * Set workflowplans_id
     * @param string $workflowplansId
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setWorkflowplansId($workflowplansId)
    {
        return $this->setData(self::WORKFLOWPLANS_ID, $workflowplansId);
    }

    /**
     * Get plan_id
     * @return string
     */
    public function getPlanId()
    {
        return $this->getData(self::PLAN_ID);
    }

    /**
     * Set plan_id
     * @param string $plan_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setPlanId($plan_id)
    {
        return $this->setData(self::PLAN_ID, $plan_id);
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
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setScheduleId($schedule_id)
    {
        return $this->setData(self::SCHEDULE_ID, $schedule_id);
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
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
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
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setStoreId($store_id)
    {
        return $this->setData(self::STORE_ID, $store_id);
    }

    /**
     * Get schedule_name
     * @return string
     */
    public function getScheduleName()
    {
        return $this->getData(self::SCHEDULE_NAME);
    }

    /**
     * Set schedule_name
     * @param string $schedule_name
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setScheduleName($schedule_name)
    {
        return $this->setData(self::SCHEDULE_NAME, $schedule_name);
    }

    /**
     * Get relation_id
     * @return string
     */
    public function getRelationId()
    {
        return $this->getData(self::RELATION_ID);
    }

    /**
     * Set relation_id
     * @param string $relation_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setRelationId($relation_id)
    {
        return $this->setData(self::RELATION_ID, $relation_id);
    }

    /**
     * Get start_time
     * @return string
     */
    public function getStartTime()
    {
        return $this->getData(self::START_TIME);
    }

    /**
     * Set start_time
     * @param string $start_time
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setStartTime($start_time)
    {
        return $this->setData(self::START_TIME, $start_time);
    }

    /**
     * Get execution_at
     * @return string
     */
    public function getExecutionAt()
    {
        return $this->getData(self::EXECUTION_AT);
    }

    /**
     * Set execution_at
     * @param string $execution_at
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setExecutionAt($execution_at)
    {
        return $this->setData(self::EXECUTION_AT, $execution_at);
    }

    /**
     * Get end_time
     * @return string
     */
    public function getEndTime()
    {
        return $this->getData(self::END_TIME);
    }

    /**
     * Set end_time
     * @param string $end_time
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setEndTime($end_time)
    {
        return $this->setData(self::END_TIME, $end_time);
    }

    /**
     * Get status
     * @return string
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set status
     * @param string $status
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get message
     * @return string
     */
    public function getMessage()
    {
        return $this->getData(self::MESSAGE);
    }

    /**
     * Set message
     * @param string $message
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowPlansInterface
     */
    public function setMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }
}
