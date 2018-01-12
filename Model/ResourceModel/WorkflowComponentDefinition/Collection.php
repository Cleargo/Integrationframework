<?php


namespace Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowComponentDefinition;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Cleargo\Integrationframeworks\Model\WorkflowComponentDefinition',
            'Cleargo\Integrationframeworks\Model\ResourceModel\WorkflowComponentDefinition'
        );
    }


    protected function _afterLoad()
    {
        $component_ids = array();
        //get all result item's workflowcomponentdefinition_id
        foreach ($this as $item) {
            $component_ids[] = $item->getData("workflowcomponentdefinition_id");
        }

        //get all param data
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable())
            ->joinLeft(
                ['param' => $this->getTable('workflow_component_definition_parameters')],
                $this->getMainTable() . '.workflowcomponentdefinition_id = param.component_id'
            )
            ->where('workflowcomponentdefinition_id IN (?)', $component_ids)
        ;
        //echo $select;
        $result = $connection->fetchAll($select);
        $parameters = array();
        foreach($result as $sub_result) {
            $parameters[$sub_result['component_id']][]= $sub_result;
        }

        foreach ($this as  $item){

            $workflow_component_definition_id = $item['workflowcomponentdefinition_id'];
            $item->setData("parameters",$parameters[$workflow_component_definition_id]);
        }

    }

}




