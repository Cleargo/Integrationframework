<?php


namespace Cleargo\Integrationframeworks\Api\Data;

interface WorkflowComponentDefinitionInterface
{

    const TYPE = 'type';
    const WORKFLOWCOMPONENTDEFINITION_ID = 'workflowcomponentdefinition_id';
    const DESCRIPTION = 'description';
    const COMPONENT_ID = 'component_id';
    const NAME = 'name';


    /**
     * Get workflowcomponentdefinition_id
     * @return string|null
     */
    public function getWorkflowcomponentdefinitionId();

    /**
     * Set workflowcomponentdefinition_id
     * @param string $workflowcomponentdefinition_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface
     */
    public function setWorkflowcomponentdefinitionId($workflowcomponentdefinitionId);

    /**
     * Get component_id
     * @return string|null
     */
    public function getComponentId();

    /**
     * Set component_id
     * @param string $component_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface
     */
    public function setComponentId($component_id);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface
     */
    public function setName($name);

    /**
     * Get type
     * @return string|null
     */
    public function getType();

    /**
     * Set type
     * @param string $type
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface
     */
    public function setType($type);

    /**
     * Get description
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     * @param string $description
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface
     */
    public function setDescription($description);
}
