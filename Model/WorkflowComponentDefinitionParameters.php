<?php


namespace Cleargo\Integrationframeworks\Model;

use Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface;

class WorkflowComponentDefinitionParameters extends \Magento\Framework\Model\AbstractModel implements WorkflowComponentDefinitionParametersInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowComponentDefinitionParameters');
    }

    /**
     * Get workflowcomponentdefinitionparameters_id
     * @return string
     */
    public function getWorkflowcomponentdefinitionparametersId()
    {
        return $this->getData(self::WORKFLOWCOMPONENTDEFINITIONPARAMETERS_ID);
    }

    /**
     * Set workflowcomponentdefinitionparameters_id
     * @param string $workflowcomponentdefinitionparametersId
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface
     */
    public function setWorkflowcomponentdefinitionparametersId($workflowcomponentdefinitionparametersId)
    {
        return $this->setData(self::WORKFLOWCOMPONENTDEFINITIONPARAMETERS_ID, $workflowcomponentdefinitionparametersId);
    }

    /**
     * Get component_param_id
     * @return string
     */
    public function getComponentParamId()
    {
        return $this->getData(self::COMPONENT_PARAM_ID);
    }

    /**
     * Set component_param_id
     * @param string $component_param_id
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface
     */
    public function setComponentParamId($component_param_id)
    {
        return $this->setData(self::COMPONENT_PARAM_ID, $component_param_id);
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
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface
     */
    public function setComponentId($component_id)
    {
        return $this->setData(self::COMPONENT_ID, $component_id);
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
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get type
     * @return string
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Set type
     * @param string $type
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * Get required
     * @return string
     */
    public function getRequired()
    {
        return $this->getData(self::REQUIRED);
    }

    /**
     * Set required
     * @param string $required
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface
     */
    public function setRequired($required)
    {
        return $this->setData(self::REQUIRED, $required);
    }

    /**
     * Get enum_values
     * @return string
     */
    public function getEnumValues()
    {
        return $this->getData(self::ENUM_VALUES);
    }

    /**
     * Set enum_values
     * @param string $enum_values
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionParametersInterface
     */
    public function setEnumValues($enum_values)
    {
        return $this->setData(self::ENUM_VALUES, $enum_values);
    }
}
