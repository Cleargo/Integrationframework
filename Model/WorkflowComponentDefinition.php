<?php


namespace Cleargo\Integrationframeworks\Model;

use Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface;

class WorkflowComponentDefinition extends \Magento\Framework\Model\AbstractModel implements WorkflowComponentDefinitionInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowComponentDefinition');
    }

    /**
     * Get workflowcomponentdefinition_id
     * @return string
     */
    public function getWorkflowcomponentdefinitionId()
    {
        return $this->getData(self::WORKFLOWCOMPONENTDEFINITION_ID);
    }

    /**
     * Set workflowcomponentdefinition_id
     * @param string $workflowcomponentdefinitionId
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface
     */
    public function setWorkflowcomponentdefinitionId($workflowcomponentdefinitionId)
    {
        return $this->setData(self::WORKFLOWCOMPONENTDEFINITION_ID, $workflowcomponentdefinitionId);
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
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface
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
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface
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
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
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
     * @return \Cleargo\Integrationframeworks\Api\Data\WorkflowComponentDefinitionInterface
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }
}
