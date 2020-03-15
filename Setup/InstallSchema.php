<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\ShippingRateByProductAttribute\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $ratesTable = $installer->getTable(\MageWorx\ShippingRules\Model\Carrier::RATE_TABLE_NAME);

        /**
         * Create table 'mageworx_shippingrules_rates_shippingnew'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('mageworx_shippingrules_rates_shippingnew')
        )->addColumn(
            'rate_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Rate Id'
        )->addColumn(
            'shippingnew',
            Table::TYPE_TEXT,
            '120',
            ['nullable' => false],
            'shippingnew attribute value'
        )->addForeignKey(
            $installer->getFkName('mageworx_shippingrules_rates_shippingnew', 'rate_id', $ratesTable, 'rate_id'),
            'rate_id',
            $ratesTable,
            'rate_id',
            Table::ACTION_CASCADE
        )->addIndex(
            $installer->getIdxName(
                'mageworx_shippingrules_rates_product_attributes',
                ['rate_id', 'shippingnew'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['rate_id', 'shippingnew'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        )->setComment(
            'Product Attributes For Shipping Suite Rates'
        );

        $installer->getConnection()->createTable($table);
    }
}
