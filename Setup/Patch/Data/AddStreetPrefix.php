<?php

namespace SystemCode\BrazilCustomerAttributes\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Model\Indexer\Address\AttributeProvider;

/**
 * Class AddStreetPrefix
 * @package SystemCode\BrazilCustomerAttributes\Setup\Patch\Data
 */
class AddStreetPrefix implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var CustomerSetup
     */
    private $customerSetupFactory;

    /**
     * Constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $customerSetup->addAttribute(AttributeProvider::ENTITY, 'street_prefix', [
            'label' => 'Street Prefix',
            'input' => 'select',
            'type' => 'varchar',
            'source' => 'SystemCode\BrazilCustomerAttributes\Model\Config\Source\Streetprefix',
            'required' => true,
            'position' => 65,
            'visible' => true,
            'system' => false,
            'is_used_in_grid' => false,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => false,
            'is_searchable_in_grid' => false,
            'backend' => ''
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute('customer_address', 'street_prefix')->addData([
            'used_in_forms' => [
                'adminhtml_customer_address',
                'customer_address_edit',
                'customer_register_address'
            ]
        ]);
        $attribute->save();

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public function revert()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [

        ];
    }
}
