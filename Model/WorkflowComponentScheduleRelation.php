<?php


namespace Cleargo\Integrationframeworks\Model;

use Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface;

class WorkflowComponentScheduleRelation extends \Magento\Framework\Model\AbstractModel implements WorkflowComponentScheduleRelationInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowComponentScheduleRelation');
    }

    /**
     * Get workflowcomponentschedulerelation_id
     * @return string
     */
    public function getWorkflowcomponentschedulerelationId()
    {
        return $this->getData(self::WORKFLOWCOMPONENTSCHEDULERELATION_ID);
    }

    /**
     * Set workflowcomponentschedulerelation_id
     * @param string $workflowcomponentschedulerelationId
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface
     */
    public function setWorkflowcomponentschedulerelationId($workflowcomponentschedulerelationId)
    {
        return $this->setData(self::WORKFLOWCOMPONENTSCHEDULERELATION_ID, $workflowcomponentschedulerelationId);
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
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface
     */
    public function setRelationId($relation_id)
    {
        return $this->setData(self::RELATION_ID, $relation_id);
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
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface
     */
    public function setScheduleId($schedule_id)
    {
        return $this->setData(self::SCHEDULE_ID, $schedule_id);
    }

    /**
     * Get asynchron
     * @return string
     */
    public function getAsynchron()
    {
        return $this->getData(self::ASYNCHRON);
    }

    /**
     * Set asynchron
     * @param string $asynchron
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface
     */
    public function setAsynchron($asynchron)
    {
        return $this->setData(self::ASYNCHRON, $asynchron);
    }

    /**
     * Get component_id
     * @return string
     */
    public function getComponentId()
    {
        return $this->getData(self::COMPONENT_ID);
    }

    /**
     * Set component_id
     * @param string $component_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface
     */
    public function setComponentId($component_id)
    {
        return $this->setData(self::COMPONENT_ID, $component_id);
    }

    /**
     * Get position
     * @return string
     */
    public function getPosition()
    {
        return $this->getData(self::POSITION);
    }

    /**
     * Set position
     * @param string $position
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface
     */
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, $position);
    }

    /**
     * Get parameters
     * @return string
     */
    public function getParameters()
    {
        return $this->getData(self::PARAMETERS);
    }

    /**
     * Set parameters
     * @param string $parameters
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface
     */
    public function setParameters($parameters)
    {
        return $this->setData(self::PARAMETERS, $parameters);
    }
}
