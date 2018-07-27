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

            $setup->getConnection()->query(
                "INSERT INTO `workflow_schedule` 
                  (`workflowschedule_id`, `enable`, `name`, `description`, `website_id`, `store_id`, `notification_email`, `execution_active_from`, `execution_interval`, `execution_type`, `file_log_level`, `schedule_type`, `sort_order`) 
                      VALUES 
                  (1, 1, 'Import Shipment', 'For creating shipping of order in magento', 1, 1, 'leo@cleargo.com', '2018-01-10 18:58:49', 10, 'MIN', 'INFO', 'RECURRING', 1);"
            );
            $setup->getConnection()->query(
                "INSERT INTO `workflow_schedule` 
                  (`workflowschedule_id`, `enable`, `name`, `description`, `website_id`, `store_id`, `notification_email`, `execution_active_from`, `execution_interval`, `execution_type`, `file_log_level`, `schedule_type`, `sort_order`) 
                    VALUES 
                  (2, 1, 'Import Inventory', 'For importing inventory into magento', 1, 1, 'leo@cleargo.com', '2018-01-10 18:58:49', 1, 'HOUR', 'INFO', 'SINGLE', 2);"
            );
            $setup->getConnection()->query(
                "INSERT INTO `workflow_schedule` 
                  (`workflowschedule_id`, `enable`, `name`, `description`, `website_id`, `store_id`, `notification_email`, `execution_active_from`, `execution_interval`, `execution_type`, `file_log_level`, `schedule_type`, `sort_order`) 
                    VALUES 
                  (3, 1, 'Export Order', 'For export order into csv', 1, 1, 'leo@cleargo.com', '2018-01-10 18:58:49', 5, 'MIN', 'INFO', 'RECURRING', 3);"
            );
            $setup->getConnection()->query(
                "INSERT INTO `workflow_schedule` 
                  (`workflowschedule_id`, `enable`, `name`, `description`, `website_id`, `store_id`, `notification_email`, `execution_active_from`, `execution_interval`, `execution_type`, `file_log_level`, `schedule_type`, `sort_order`) 
                    VALUES 
                  (4, 1, 'Export Creditmemo', 'For export creditmemo', 1, 1, 'leo@cleargo.com', '2018-01-11 02:58:49', 10, 'MIN', 'INFO', 'RECURRING', 4);"
            );

            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_definition` 
                  (`workflowcomponentdefinition_id`, `name`, `type`, `description`) 
                    VALUES 
                  (1, 'DownloadFileFromFTP', 'Custom', 'For downloading file from ftp ');"
            );
            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_definition` 
                (`workflowcomponentdefinition_id`, `name`, `type`, `description`) 
                    VALUES 
                (2, 'ImportShipment', 'Custom', 'For importing shipment');"
            );
            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_definition` 
                (`workflowcomponentdefinition_id`, `name`, `type`, `description`) 
                    VALUES 
                (3, 'ExportOrder', 'Magento', 'For export order');"
            );
            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_definition` 
                (`workflowcomponentdefinition_id`, `name`, `type`, `description`) 
                    VALUES 
                (4, 'UploadFileToFTP', 'Custom', 'For uploading file to ftp');"
            );
            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_definition` 
                (`workflowcomponentdefinition_id`, `name`, `type`, `description`) 
                    VALUES 
                (5, 'ImportInventory', 'Custom', 'For importing inventory');"
            );
            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_definition` 
                (`workflowcomponentdefinition_id`, `name`, `type`, `description`) 
                    VALUES 
                (6, 'ExportCreditmemo', 'Magento', 'For export creditmemo');"
            );
            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_definition` 
                (`workflowcomponentdefinition_id`, `name`, `type`, `description`) 
                    VALUES 
                (7, 'SmbDownloader', 'Custom', 'For downloading file from smb ');"
            );
            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_definition` 
                (`workflowcomponentdefinition_id`, `name`, `type`, `description`) 
                    VALUES 
                (8, 'SmbUploader', 'Custom', 'For uploading file to smb');"
            );

            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (1, 1, 'ftp_host', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (2, 1, 'ftp_user', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (3, 1, 'ftp_pw', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (4, 1, 'secure_type', 'enum', 1, '{\'ssl\':\'no-ssl\'}');");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (5, 1, 'file_pattern', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (6, 1, 'source_path', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (7, 1, 'destination_path', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (8, 2, 'send_email_to_customer', 'bool', 0, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (9, 2, 'import_path', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (10, 2, 'archive_path', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (11, 3, 'order_status', 'enum', 1, '{\"enumValues\":[\"all\",\"confirmed and paid\"]}');");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (12, 3, 'export_path', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (13, 4, 'ftp_host', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (14, 4, 'ftp_user', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (15, 4, 'ftp_pw', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (16, 4, 'secure_type', 'enum', 1, '{\'ssl\':\'no-ssl\'}');");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (17, 4, 'file_pattern', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (18, 4, 'upload_path', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (19, 4, 'source_path', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (20, 5, 'reindex', 'int', 0, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (21, 6, 'creditmemo_status', 'enum', 1, '{\"enumValues\":[\"all\",\"confirmed and paid\"]}');");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (22, 6, 'export_path', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (23, 7, 'smb_host', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (24, 7, 'smb_user', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (25, 7, 'smb_pw', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (26, 7, 'smb_path', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (27, 7, 'local_path', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (28, 7, 'smb_share', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (29, 8, 'smb_user', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (30, 8, 'smb_pw', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (31, 8, 'smb_pw', 'varchar', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (32, 8, 'smb_path', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (33, 8, 'local_path', 'text', 1, NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_component_definition_parameters` (`workflowcomponentdefinitionparameters_id`, `component_id`, `name`, `type`, `required`, `enum_values`) VALUES (34, 8, 'smb_share', 'text', 1, NULL);");

//            $setup->getConnection()->query(
//"INSERT INTO `workflow_component_schedule_relation`
    //                  (`workflowcomponentschedulerelation_id`, `schedule_id`, `asynchron`, `component_id`, `position`, `parameters`)
//      VALUES
    // (1, 1, 0, 1, 1, '{\"ftp_host\":\"202.181.236.121\",\"ftp_user\":\"integration\",\"ftp_pw\":\"cleargoisthebest\",\"secure_type\":\"no-ssl\",\"file_pattern\":\"^shipment_[Ymd.xml\",\"destination_path\":\"/var/nav/import/shipment/\",\"source_path\":\"/k11/dev/nav/export/shipment/\"}');"
            //);
            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_schedule_relation` 
                    (`workflowcomponentschedulerelation_id`, `schedule_id`, `asynchron`, `component_id`, `position`, `parameters`) 
                        VALUES 
                    (1, 1, 0, 7, 1, '{\"smb_host\":\"10.11.1.144\",\"smb_user\":\"tectura\",\"smb_pw\":\"P@556#78\",\"local_path\":\"/var/nav/import/shipment/\",\"smb_path\":\"Shipment/\",\"smb_share\":\"EShop/\"}');"
            );
            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_schedule_relation` 
                    (`workflowcomponentschedulerelation_id`, `schedule_id`, `asynchron`, `component_id`, `position`, `parameters`) 
                        VALUES 
                    (2, 1, 0, 2, 2, '{\"sent_email_to_customer\":\"N\",\"import_path\":\"/var/nav/import/shipment/\",\"archive_path\":\"/var/nav/import/archive/shipment/\"}');"
            );
            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_schedule_relation`
                      (`workflowcomponentschedulerelation_id`, `schedule_id`, `asynchron`, `component_id`, `position`, `parameters`)
                      VALUES
                    (3, 2, 0, 1, 1, '{\"ftp_host\":\"202.181.236.121\",\"ftp_user\":\"integration\",\"ftp_pw\":\"cleargoisthebest\",\"secure_type\":\"no-ssl\",\"file_pattern\":\"^shipment_[Ymd.xml\"}');"
            );
            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_schedule_relation` 
                    (`workflowcomponentschedulerelation_id`, `schedule_id`, `asynchron`, `component_id`, `position`, `parameters`) 
                        VALUES 
                    (4, 2, 0, 5, 1, '{\"reindex\":\"1\"}');"
            );
            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_schedule_relation` 
                    (`workflowcomponentschedulerelation_id`, `schedule_id`, `asynchron`, `component_id`, `position`, `parameters`) 
                        VALUES 
                    (5, 3, 0, 3, 1, '{\"order_status\":\"processing\",\"export_path\":\"/var/nav/export/order/\"}');"
            );
/*            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_schedule_relation`
                    (`workflowcomponentschedulerelation_id`, `schedule_id`, `asynchron`, `component_id`, `position`, `parameters`)
                        VALUES
                    (6, 3, 0, 4, 2, '{\"ftp_host\":\"202.181.236.121\",\"ftp_user\":\"integration\",\"ftp_pw\":\"cleargoisthebest\",\"secure_type\":\"no-ssl\",\"file_pattern\":\"^shipment_[Ymd.xml\",\"upload_path\":\"/k11/dev/magento/export/order/\",\"source_path\":\"/var/nav/export/order/\"}');"
            );*/
            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_schedule_relation` 
                    (`workflowcomponentschedulerelation_id`, `schedule_id`, `asynchron`, `component_id`, `position`, `parameters`) 
                        VALUES 
                    (6, 3, 0, 8, 2, '{\"smb_host\":\"10.11.1.144\",\"smb_user\":\"tectura\",\"smb_pw\":\"P@556#78\",\"local_path\":\"/var/nav/export/order/\",\"smb_path\":\"Orders/\",\"smb_share\":\"EShop/\"}');"
            );
            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_schedule_relation` 
                    (`workflowcomponentschedulerelation_id`, `schedule_id`, `asynchron`, `component_id`, `position`, `parameters`) 
                        VALUES 
                    (7, 4, 0, 6, 1, '{\"creditmemo_status\":\"confirmed\",\"export_path\":\"/var/nav/export/creditmemo/\"}');"
            );
/*            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_schedule_relation` 
                    (`workflowcomponentschedulerelation_id`, `schedule_id`, `asynchron`, `component_id`, `position`, `parameters`) 
                        VALUES 
                    (8, 4, 0, 4, 2, '{\"ftp_host\":\"202.181.236.121\",\"ftp_user\":\"integration\",\"ftp_pw\":\"cleargoisthebest\",\"secure_type\":\"no-ssl\",\"file_pattern\":\"^shipment_[Ymd.xml\",\"upload_path\":\"/k11/dev/magento/export/creditmemo/\",\"source_path\":\"/var/nav/export/creditmemo/\"}');"
            );*/
            $setup->getConnection()->query(
                "INSERT INTO `workflow_component_schedule_relation` 
                    (`workflowcomponentschedulerelation_id`, `schedule_id`, `asynchron`, `component_id`, `position`, `parameters`) 
                        VALUES 
                    (8, 4, 0, 8, 2, '{\"smb_host\":\"10.11.1.144\",\"smb_user\":\"tectura\",\"smb_pw\":\"P@556#78\",\"local_path\":\"/var/nav/export/creditmemo/\",\"smb_path\":\"Credit Memos/\",\"smb_share\":\"EShop/\"}');"
            );

            $setup->getConnection()->query("INSERT INTO `workflow_plans` (`workflowplans_id`, `schedule_id`, `website_id`, `store_id`, `schedule_name`, `relation_id`, `start_time`, `execution_at`, `end_time`, `status`, `message`) VALUES (1, 3, 1, 1, 'ExportOrder', NULL, '2018-01-16 11:42:30', NULL, NULL, 'completed', NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_plans` (`workflowplans_id`, `schedule_id`, `website_id`, `store_id`, `schedule_name`, `relation_id`, `start_time`, `execution_at`, `end_time`, `status`, `message`) VALUES (2, 4, 1, 1, 'ExportCreditmemo', NULL, '2018-01-16 13:04:13', NULL, NULL, 'completed', NULL);");
            $setup->getConnection()->query("INSERT INTO `workflow_plans` (`workflowplans_id`, `schedule_id`, `website_id`, `store_id`, `schedule_name`, `relation_id`, `start_time`, `execution_at`, `end_time`, `status`, `message`) VALUES (3, 1, 1, 1, 'ImportShipment', NULL, '2018-01-16 21:04:13', NULL, NULL, 'completed', NULL);");

        }
        $setup->endSetup();
    }
}
