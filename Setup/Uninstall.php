<?php

namespace Cleargo\Integrationframeworks\Setup;

class Uninstall
{
    protected $resources;

    public function uninstall() {
        $this->resources = \Magento\Framework\App\ObjectManager::getInstance()
            ->get('Magento\Framework\App\ResourceConnection');
        $connection= $this->resources->getConnection();

        $sql = "DROP TABLE IF EXISTS workflow_component_definition_parameters;";
        $connection->query($sql);

        $sql = "DROP TABLE IF EXISTS workflow_plans;";
        $connection->query($sql);

        $sql = "DROP TABLE IF EXISTS workflow_component_schedule_relation;";
        $connection->query($sql);

        $sql = "DROP TABLE IF EXISTS workflow_component_definition;";
        $connection->query($sql);


        $sql = "DROP TABLE IF EXISTS workflow_schedule;";
        $connection->query($sql);

        
        $sql = "DELETE from setup_module where module = 'Cleargo_Integrationframeworks'";
        $connection->query($sql);


    }
}