<?php


namespace Cleargo\Integrationframeworks\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\InstallSchemaInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        $table_cleargo_integrationframeworks_workflowschedule = $setup->getConnection()->newTable($setup->getTable('cleargo_integrationframeworks_workflowschedule'));

        
        $table_cleargo_integrationframeworks_workflowschedule->addColumn(
            'workflowschedule_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,'auto_increment' => true),
            'Entity ID'
        );
        



        
        $table_cleargo_integrationframeworks_workflowschedule->addColumn(
            'enable',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            [],
            'enable'
        );
        

        
        $table_cleargo_integrationframeworks_workflowschedule->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'name'
        );
        

        
        $table_cleargo_integrationframeworks_workflowschedule->addColumn(
            'description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'description'
        );
        

        
        $table_cleargo_integrationframeworks_workflowschedule->addColumn(
            'website_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'website_id'
        );
        

        
        $table_cleargo_integrationframeworks_workflowschedule->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'store_id'
        );
        

        
        $table_cleargo_integrationframeworks_workflowschedule->addColumn(
            'notification_email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'notification_email'
        );
        

        
        $table_cleargo_integrationframeworks_workflowschedule->addColumn(
            'execution_active_from',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            [],
            'execution_active_from'
        );
        

        
        $table_cleargo_integrationframeworks_workflowschedule->addColumn(
            'execution_interval',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'execution_interval'
        );
        

        
        $table_cleargo_integrationframeworks_workflowschedule->addColumn(
            'execution_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'execution_type'
        );
        

        
        $table_cleargo_integrationframeworks_workflowschedule->addColumn(
            'file_log_level',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'file_log_level'
        );
        

        
        $table_cleargo_integrationframeworks_workflowschedule->addColumn(
            'schedule_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'schedule_type'
        );
        

        
        $table_cleargo_integrationframeworks_workflowschedule->addColumn(
            'sort_order',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'sort_order'
        );
        

        $table_cleargo_integrationframeworks_workflowcomponentschedulerelation = $setup->getConnection()->newTable($setup->getTable('cleargo_integrationframeworks_workflowcomponentschedulerelation'));

        
        $table_cleargo_integrationframeworks_workflowcomponentschedulerelation->addColumn(
            'workflowcomponentschedulerelation_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,'auto_increment' => true),
            'Entity ID'
        );
        


        
        $table_cleargo_integrationframeworks_workflowcomponentschedulerelation->addColumn(
            'schedule_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'forieign key'
        );
        

        
        $table_cleargo_integrationframeworks_workflowcomponentschedulerelation->addColumn(
            'asynchron',
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            null,
            [],
            'asynchron'
        );
        

        
        $table_cleargo_integrationframeworks_workflowcomponentschedulerelation->addColumn(
            'component_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'foreign key'
        );
        

        
        $table_cleargo_integrationframeworks_workflowcomponentschedulerelation->addColumn(
            'position',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'position'
        );
        

        
        $table_cleargo_integrationframeworks_workflowcomponentschedulerelation->addColumn(
            'parameters',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'parameters'
        );
        

        $table_cleargo_integrationframeworks_workflowcomponentdefinition = $setup->getConnection()->newTable($setup->getTable('cleargo_integrationframeworks_workflowcomponentdefinition'));

        
        $table_cleargo_integrationframeworks_workflowcomponentdefinition->addColumn(
            'workflowcomponentdefinition_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,'auto_increment' => true),
            'Entity ID'
        );
        



        
        $table_cleargo_integrationframeworks_workflowcomponentdefinition->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'name'
        );
        

        
        $table_cleargo_integrationframeworks_workflowcomponentdefinition->addColumn(
            'type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'type'
        );
        

        
        $table_cleargo_integrationframeworks_workflowcomponentdefinition->addColumn(
            'description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'description'
        );
        

        $table_cleargo_integrationframeworks_workflowcomponentdefinitionparameters = $setup->getConnection()->newTable($setup->getTable('cleargo_integrationframeworks_workflowcomponentdefinitionparameters'));

        
        $table_cleargo_integrationframeworks_workflowcomponentdefinitionparameters->addColumn(
            'workflowcomponentdefinitionparameters_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,'auto_increment' => true),
            'Entity ID'
        );
        

        


        
        $table_cleargo_integrationframeworks_workflowcomponentdefinitionparameters->addColumn(
            'component_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'foreign key'
        );
        

        
        $table_cleargo_integrationframeworks_workflowcomponentdefinitionparameters->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'name'
        );
        

        
        $table_cleargo_integrationframeworks_workflowcomponentdefinitionparameters->addColumn(
            'type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'type'
        );
        

        
        $table_cleargo_integrationframeworks_workflowcomponentdefinitionparameters->addColumn(
            'required',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'required'
        );
        

        
        $table_cleargo_integrationframeworks_workflowcomponentdefinitionparameters->addColumn(
            'enum_values',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'enum_values'
        );
        

        $table_cleargo_integrationframeworks_workflowplans = $setup->getConnection()->newTable($setup->getTable('cleargo_integrationframeworks_workflowplans'));

        
        $table_cleargo_integrationframeworks_workflowplans->addColumn(
            'workflowplans_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,'auto_increment' => true),
            'Entity ID'
        );
        


        
        $table_cleargo_integrationframeworks_workflowplans->addColumn(
            'schedule_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'foreign key'
        );
        

        
        $table_cleargo_integrationframeworks_workflowplans->addColumn(
            'website_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'website_id'
        );
        

        
        $table_cleargo_integrationframeworks_workflowplans->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'store_id'
        );
        

        
        $table_cleargo_integrationframeworks_workflowplans->addColumn(
            'schedule_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'schedule_name'
        );
        

        
        $table_cleargo_integrationframeworks_workflowplans->addColumn(
            'relation_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'foreign key'
        );
        

        
        $table_cleargo_integrationframeworks_workflowplans->addColumn(
            'start_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            [],
            'start_time'
        );
        

        
        $table_cleargo_integrationframeworks_workflowplans->addColumn(
            'execution_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            [],
            'execution_at'
        );
        

        
        $table_cleargo_integrationframeworks_workflowplans->addColumn(
            'end_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            [],
            'end_time'
        );
        

        
        $table_cleargo_integrationframeworks_workflowplans->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'status'
        );
        

        
        $table_cleargo_integrationframeworks_workflowplans->addColumn(
            'message',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'message'
        );
        

        $setup->getConnection()->createTable($table_cleargo_integrationframeworks_workflowplans);

        $setup->getConnection()->createTable($table_cleargo_integrationframeworks_workflowcomponentdefinitionparameters);

        $setup->getConnection()->createTable($table_cleargo_integrationframeworks_workflowcomponentdefinition);

        $setup->getConnection()->createTable($table_cleargo_integrationframeworks_workflowcomponentschedulerelation);

        $setup->getConnection()->createTable($table_cleargo_integrationframeworks_workflowschedule);

        $setup->endSetup();
    }
}
