<?php


namespace Cleargo\Integrationframeworks\Api\Data;

interface WorkflowComponentScheduleRelationInterface
{

    const SCHEDULE_ID = 'schedule_id';
    const COMPONENT_ID = 'component_id';
    const WORKFLOWCOMPONENTSCHEDULERELATION_ID = 'workflowcomponentschedulerelation_id';
    const RELATION_ID = 'relation_id';
    const POSITION = 'position';
    const PARAMETERS = 'parameters';
    const ASYNCHRON = 'asynchron';


    /**
     * Get workflowcomponentschedulerelation_id
     * @return string|null
     */
    public function getWorkflowcomponentschedulerelationId();

    /**
     * Set workflowcomponentschedulerelation_id
     * @param string $workflowcomponentschedulerelation_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface
     */
    public function setWorkflowcomponentschedulerelationId($workflowcomponentschedulerelationId);

    /**
     * Get relation_id
     * @return string|null
     */
    public function getRelationId();

    /**
     * Set relation_id
     * @param string $relation_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface
     */
    public function setRelationId($relation_id);

    /**
     * Get schedule_id
     * @return string|null
     */
    public function getScheduleId();

    /**
     * Set schedule_id
     * @param string $schedule_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface
     */
    public function setScheduleId($schedule_id);

    /**
     * Get asynchron
     * @return string|null
     */
    public function getAsynchron();

    /**
     * Set asynchron
     * @param string $asynchron
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface
     */
    public function setAsynchron($asynchron);

    /**
     * Get component_id
     * @return string|null
     */
    public function getComponentId();

    /**
     * Set component_id
     * @param string $component_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface
     */
    public function setComponentId($component_id);

    /**
     * Get position
     * @return string|null
     */
    public function getPosition();

    /**
     * Set position
     * @param string $position
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface
     */
    public function setPosition($position);

    /**
     * Get parameters
     * @return string|null
     */
    public function getParameters();

    /**
     * Set parameters
     * @param string $parameters
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentScheduleRelationInterface
     */
    public function setParameters($parameters);
}
