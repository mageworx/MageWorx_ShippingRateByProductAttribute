<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\ShippingRateByProductAttribute\Setup;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\InstallDataInterface;

/**
 * Class InstallData
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * UpgradeData constructor.
     *
     * @param EavSetupFactory $eavSetupFactory
     * @param MetadataPool $metadataPool
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        MetadataPool $metadataPool
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->metadataPool    = $metadataPool;
    }

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create();

        $exampleAttribute = $eavSetup->getAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'shippingnew'
        );
        if (empty($exampleAttribute)) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'shippingnew',
                [
                    'group'                    => 'General',
                    'type'                     => 'int',
                    'label'                    => 'Shipping Category',
                    'input'                    => 'select',
                    'required'                 => false,
                    'sort_order'               => 900,
                    'global'                   => ScopedAttributeInterface::SCOPE_STORE,
                    'is_used_in_grid'          => true,
                    'is_visible_in_grid'       => true,
                    'is_filterable_in_grid'    => true,
                    'visible'                  => true,
                    'is_html_allowed_on_front' => true,
                    'visible_on_front'         => true,
                    'system'                   => 0,
                    'default'                  => null,
                    'user_defined'             => false, // should be false to prevent deleting from admin-side interface
                    'source'                   => 'MageWorx\ShippingRateByProductAttribute\Model\Config\Source\ShippingCategory',
                    'apply_to'                 => 'simple,grouped,configurable,downloadable,virtual,bundle'
                ]
            );
        }
    }
}
