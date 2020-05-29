<?php

namespace SystemCode\BrazilCustomerAttributes\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class UpgradeSchema
 * @package SystemCode\BrazilCustomerAttributes\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();

        if (version_compare($context->getVersion(), "1.0.1", "<")) {
            $this->addStreetPrefix($connection, $setup);
        }

        $installer->endSetup();
    }

    /**
     * @param $connection
     * @param $setup
     */
    private function addStreetPrefix($connection, $setup)
    {
        if ($connection->tableColumnExists($setup->getTable('sales_order_address'), 'street_prefix') === false) {
            $connection->addColumn(
                    $setup->getTable('sales_order_address'),
                    'street_prefix',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 20,
                        'nullable' => true,
                        'after' => 'lastname',
                        'comment' => 'Street Prefix'
                    ]
                );
        }

        if ($connection->tableColumnExists($setup->getTable('quote_address'), 'street_prefix') === false) {
            $connection->addColumn(
                $setup->getTable('quote_address'),
                'street_prefix',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 20,
                    'nullable' => true,
                    'after' => 'company',
                    'comment' => 'Street Prefix'
                ]
            );
        }
    }
}