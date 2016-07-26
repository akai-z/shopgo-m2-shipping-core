<?php
/**
 * Copyright © 2016 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ShopGo\ShippingCore\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\Product\Type as ProductType;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Attribute set and attribute set group
     */
    const ATTRIBUTE_SET_GROUP = 'Product Details';

    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $productTypes = [
            ProductType::TYPE_SIMPLE,
            ProductType::TYPE_BUNDLE
        ];
        $productTypes = join(',', $productTypes);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'hs_code',
            [
                'type' => 'varchar',
                'label' => 'Harmonized System (HS) Code',
                'input' => 'text',
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => self::ATTRIBUTE_SET_GROUP,
                'used_for_promo_rules' => true,
                'visible_in_advanced_search' => true,
                'searchable' => true,
                'comparable' => true,
                'system' => false,
                'user_defined' => true,
                'visible' => true,
                'apply_to' => $productTypes
            ]
        );
    }
}
