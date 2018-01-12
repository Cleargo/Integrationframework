<?php


namespace Cleargo\Integrationframeworks\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();
        if (version_compare($context->getVersion(), "1.0.0", "<")) {
        //Your upgrade script

            $setup->getConnection()->query("INSERT INTO `workflow_schedule` (`workflowschedule_id`, `enable`, `name`, `description`, `website_id`, `store_id`, `notification_email`, `execution_active_from`, `execution_interval`, `execution_type`, `file_log_level`, `schedule_type`, `sort_order`) VALUES (1, 1, 'Import Shipment', 'For creating shipping of order in magento', 1, 1, 'leo@cleargo.com', '2018-01-10 18:58:49', 10, 'MIN', 'INFO', 'RECURRING', 1);");
            $setup->getConnection()->query("INSERT INTO `workflow_schedule` (`workflowschedule_id`, `enable`, `name`, `description`, `website_id`, `store_id`, `notification_email`, `execution_active_from`, `execution_interval`, `execution_type`, `file_log_level`, `schedule_type`, `sort_order`) VALUES (2, 1, 'Import Inventory', 'For importing inventory into magento', 1, 1, 'leo@cleargo.com', '2018-01-10 18:58:49', 1, 'HOUR', 'INFO', 'SINGLE', 2);");
            $setup->getConnection()->query("INSERT INTO `workflow_schedule` (`workflowschedule_id`, `enable`, `name`, `description`, `website_id`, `store_id`, `notification_email`, `execution_active_from`, `execution_interval`, `execution_type`, `file_log_level`, `schedule_type`, `sort_order`) VALUES (3, 1, 'Export Order', 'For export order into csv', 1, 1, 'leo@cleargo.com', '2018-01-10 18:58:49', 5, 'MIN', 'INFO', 'RECURRING', 3);");

            $setup->getConnection()->query("INSERT INTO `workflow_component_definition` (`workflowcomponentdefinition_id`, `name`, `type`, `description`) VALUES (1, 'DownloadFileFromFTP', 'Custom', 'For downloading file from ftp ');");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition` (`workflowcomponentdefinition_id`, `name`, `type`, `description`) VALUES (2, 'ImportShipment', 'Custom', 'For importing shipment');");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition` (`workflowcomponentdefinition_id`, `name`, `type`, `description`) VALUES (3, 'Export Order', 'Magento', 'For export order');");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition` (`workflowcomponentdefinition_id`, `name`, `type`, `description`) VALUES (4, 'UploadFileToFTP', 'Custom', 'For uploading file to ftp');");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition` (`workflowcomponentdefinition_id`, `name`, `type`, `description`) VALUES (5, 'ImportInventory', 'Custom', 'For importing inventory');");

            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (1, 1, 'ftp_host', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (2, 1, 'ftp_user', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (3, 1, 'ftp_pw', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (4, 1, 'secure_type', 'enum', 1, '{\'ssl\':\'no-ssl\'}');");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (5, 1, 'file_pattern', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (6, 2, 'send_email_to_customer', 'bool', 0, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (7, 3, 'order_status', 'enum', 1, '{\"enumValues\":[\"all\",\"confirmed and paid\"]}');");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (8, 3, 'export_path', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (9, 4, 'ftp_host', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (10, 4, 'ftp_user', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (11, 4, 'ftp_pw', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (12, 4, 'secure_type', 'enum', 1, '{\'ssl\':\'no-ssl\'}');");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (13, 4, 'file_pattern', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (14, 5, 'reindex', 'int', 0, NULL);");

            $setup->getConnection()->query("INSERT INTO `workflow_component_schedule_relation` (`workflowcomponentschedulerelation_id`, `schedule_id`, `asynchron`, `component_id`, `position`, `parameters`) VALUES (1, 1, 0, 1, 1, '{\"ftp_host\":\"ftp://128.12.123.12\",\"ftp_user\":\"cleargo\",\"ftp_pw\":\"test123\",\"secure_type\":\"no-ssl\",\"file_pattern\":\"^shipment_[Ymd.xml\"}');");
            $setup->getConnection()->query("INSERT INTO `workflow_component_schedule_relation` (`workflowcomponentschedulerelation_id`, `schedule_id`, `asynchron`, `component_id`, `position`, `parameters`) VALUES (2, 1, 0, 2, 2, '{\"sent_email_to_customer\":\'N\'}');");
            $setup->getConnection()->query("INSERT INTO `workflow_component_schedule_relation` (`workflowcomponentschedulerelation_id`, `schedule_id`, `asynchron`, `component_id`, `position`, `parameters`) VALUES (3, 2, 0, 1, 1, '{\"ftp_host\":\"ftp://128.12.123.12\",\"ftp_user\":\"cleargo\",\"ftp_pw\":\"test123\",\"secure_type\":\"no-ssl\",\"file_pattern\":\"^shipment_[Ymd.xml\"}');");
            $setup->getConnection()->query("INSERT INTO `workflow_component_schedule_relation` (`workflowcomponentschedulerelation_id`, `schedule_id`, `asynchron`, `component_id`, `position`, `parameters`) VALUES (4, 2, 0, 5, 1, '{\"reindex\":\"1\"}');");
            $setup->getConnection()->query("INSERT INTO `workflow_component_schedule_relation` (`workflowcomponentschedulerelation_id`, `schedule_id`, `asynchron`, `component_id`, `position`, `parameters`) VALUES (5, 3, 0, 3, 1, '{\"order_status\":\"confirmed and paid\",\"export_path\":\"/var/export_order/\"}');");
            $setup->getConnection()->query("INSERT INTO `workflow_component_schedule_relation` (`workflowcomponentschedulerelation_id`, `schedule_id`, `asynchron`, `component_id`, `position`, `parameters`) VALUES (6, 3, 0, 4, 2, '{\"ftp_host\":\"ftp://128.12.123.12\",\"ftp_user\":\"cleargo\",\"ftp_pw\":\"test123\",\"secure_type\":\"no-ssl\",\"file_pattern\":\"^shipment_[Ymd.xml\"}');");


        }
        $setup->endSetup();
    }
}
