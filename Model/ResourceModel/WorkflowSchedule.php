<?php


namespace Cleargo\Integrationframeworks\Model\ResourceModel;

class WorkflowSchedule extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('workflow_schedule', 'workflowschedule_id');
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function loadRelation(\Magento\Framework\Model\AbstractModel $object){
        $connection = $this->getConnection();
        var_dump($object->getId());
        //get relation
        $select = $connection->select()->joinLeft(
            ['relation' => $this->getTable('workflow_component_schedule_relation')],
            $this->getMainTable() . '.workflowschedule_id = relation.schedule_id'
        )
            ->where('schedule_id = ?', $object->getId())
            ->order('relation.position ASC');
        ;
        $result = $connection->fetchAll($select);



        $parameters = array();
        foreach($result as $sub_result) {
            //get component
            $component_id = isset($sub_result['component_id'])? $sub_result['component_id']: '';
            if ($component_id) {
                $select = $connection->select()->joinLeft(
                    ['component' => $this->getTable('workflow_component_definition')],
                    $this->getMainTable() . '.component_id = component.workflowcomponentdefinition_id'
                )
                    ->where('workflowcomponentdefinition_id = ?', $component_id)
                    ->limit(1)
                ;
                $result2 = $connection->fetchAll($select);
                $sub_result['component'] = $result2;
            }
            $parameters[]= $sub_result;
        }
        $object->setData("relation",$parameters);
        return $this;
    }

}
