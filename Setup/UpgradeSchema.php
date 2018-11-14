<?php


namespace Cleargo\Integrationframeworks\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();
        if (version_compare($context->getVersion(), "1.0.0", "<")) {
        //Your upgrade script
        }

        if (version_compare($context->getVersion(), "1.0.1", "<")) {
            //Your upgrade script
            $orderTable = 'sales_order';
            //Order table
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable($orderTable),
                    'nav_last_sync_at',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                        'comment' =>'Nav Last Sync At'
                    ]
                );
        }

        if (version_compare($context->getVersion(), "1.0.2", "<")) {
            //Your upgrade script
            $orderTable = 'sales_creditmemo';
            //Order table
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable($orderTable),
                    'nav_last_sync_at',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                        'comment' =>'Nav Last Sync At'
                    ]
                );
        }


        if (version_compare($context->getVersion(), "1.0.4", "<")) {
            $setup->getConnection()
                ->changeColumn(
                    $setup->getTable('sales_order'),
                    'nav_last_sync_at',
                    'last_integrated_at',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                        'comment' =>'last time processed by cleargo integration module'
                    ]
                );
            $setup->getConnection()
                ->changeColumn(
                    $setup->getTable('sales_creditmemo'),
                    'nav_last_sync_at',
                    'last_integrated_at',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                        'comment' =>'last time processed by cleargo integration module'
                    ]
                );
        }

        $setup->endSetup();
    }
}
