<?php

namespace SystemCode\BrazilCustomerAttributes\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Api\AttributeRepositoryInterface;

/**
 *
 * Upgrade data for module
 *
 *
 * NOTICE OF LICENSE
 *
 * @category   SystemCode
 * @package    Systemcode_BrazilCustomerAttributes
 * @author     Eduardo Diogo Dias <contato@systemcode.com.br>
 * @copyright  System Code LTDA-ME
 * @license    http://opensource.org/licenses/osl-3.0.php
 */
class UpgradeData implements UpgradeDataInterface {

    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * Constructor
     *
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeRepositoryInterface $attributeRepository
    )
    {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @inheritdoc
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $this->addStreetPrefix($setup);
        }

        $setup->endSetup();
    }

    private function addStreetPrefix($setup)
    {
        $eavSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute('customer_address', 'street_prefix', array(
            'type' => 'varchar',
            'input' => 'select',
            'label' => 'Street Prefix',
            'source' => 'SystemCode\BrazilCustomerAttributes\Model\Config\Source\Streetprefix',
            'global' => 1,
            'visible' => 1,
            'required' => false,
            'user_defined' => 1,
            'system' => 0,
            'visible_on_front' => 1,
            'group'=>'General',
            'position' => 65
        ));

        $eavSetup->getEavConfig()->getAttribute('customer_address','street_prefix')
            ->setUsedInForms(array('adminhtml_customer_address','customer_address_edit','customer_register_address'))
            ->save();
    }
}