<?php


namespace Cleargo\Integrationframeworks\Model\ResourceModel;

class WorkflowComponentDefinition extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('workflow_component_definition', 'workflowcomponentdefinition_id');
    }
    
    
    public function _afterLoad(\Magento\Framework\Model\AbstractModel $object){
        $connection = $this->getConnection();
        var_dump($object->getId());
        $select = $connection->select()->joinLeft(
            ['param' => $this->getTable('workflow_component_definition_parameters')],
            $this->getMainTable() . '.workflowcomponentdefinition_id = param.component_id'
        )
            ->where('component_id = ?', $object->getId())
        ;
        $result = $connection->fetchAll($select);
        $parameters = array();
        foreach($result as $sub_result) {
            $parameters[]= $sub_result;
        }
        $object->setData("parameters",$parameters);
        return parent::_afterLoad($object);
    }
    
    
}
