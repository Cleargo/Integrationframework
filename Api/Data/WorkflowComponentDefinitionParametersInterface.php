<?php


namespace Cleargo\Integrationframeworks\Api\Data;

interface WorkflowComponentDefinitionParametersInterface
{

    const COMPONENT_PARAM_ID = 'component_param_id';
    const COMPONENT_ID = 'component_id';
    const TYPE = 'type';
    const ENUM_VALUES = 'enum_values';
    const REQUIRED = 'required';
    const WORKFLOWCOMPONENTDEFINITIONPARAMETERS_ID = 'workflowcomponentdefinitionparameters_id';
    const NAME = 'name';


    /**
     * Get workflowcomponentdefinitionparameters_id
     * @return string|null
     */
    public function getWorkflowcomponentdefinitionparametersId();

    /**
     * Set workflowcomponentdefinitionparameters_id
     * @param string $workflowcomponentdefinitionparameters_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface
     */
    public function setWorkflowcomponentdefinitionparametersId($workflowcomponentdefinitionparametersId);

    /**
     * Get component_param_id
     * @return string|null
     */
    public function getComponentParamId();

    /**
     * Set component_param_id
     * @param string $component_param_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface
     */
    public function setComponentParamId($component_param_id);

    /**
     * Get component_id
     * @return string|null
     */
    public function getComponentId();

    /**
     * Set component_id
     * @param string $component_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface
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
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface
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
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface
     */
    public function setType($type);

    /**
     * Get required
     * @return string|null
     */
    public function getRequired();

    /**
     * Set required
     * @param string $required
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface
     */
    public function setRequired($required);

    /**
     * Get enum_values
     * @return string|null
     */
    public function getEnumValues();

    /**
     * Set enum_values
     * @param string $enum_values
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface
     */
    public function setEnumValues($enum_values);
}
