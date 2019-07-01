<?php

namespace SystemCode\BrazilCustomerAttributes\Setup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

/**
 *
 * Installs data for module
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
class InstallData implements InstallDataInterface
{
    /**
     * Customer setup factory
     *
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;
    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;
    /**
     * Init
     *
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $setup->startSetup();
        $attributesInfo = [
            'cpf' => [
                'label' => 'CPF',
                'type' => 'varchar',
                'input' => 'text',
                'position' => 1000,
                'visible' => true,
                'required' => false,
                'system' => 0,
                'user_defined' => true
            ],
            'cnpj' => [
                'label' => 'CNPJ',
                'type' => 'varchar',
                'input' => 'text',
                'position' => 1000,
                'visible' => true,
                'required' => false,
                'system' => 0,
                'user_defined' => true
            ],
            'rg' => [
                'label' => 'RG',
                'type' => 'varchar',
                'input' => 'text',
                'position' => 1100,
                'visible' => true,
                'required' => false,
                'system' => 0,
                'user_defined' => true
            ],
            'socialname' => [
                'label' => 'Social Name',
                'type' => 'varchar',
                'input' => 'text',
                'position' => 1200,
                'visible' => true,
                'required' => false,
                'system' => 0,
                'user_defined' => true
            ],
            'tradename' => [
                'label' => 'Trade Name',
                'type' => 'varchar',
                'input' => 'text',
                'position' => 1300,
                'visible' => true,
                'required' => false,
                'system' => 0,
                'user_defined' => true
            ],
            'ie' => [
                'label' => 'IE',
                'type' => 'varchar',
                'input' => 'text',
                'position' => 1400,
                'visible' => true,
                'required' => false,
                'system' => 0,
                'user_defined' => true
            ]

        ];
        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();
        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        foreach ($attributesInfo as $attributeCode => $attributeParams) {
            $customerSetup->addAttribute(Customer::ENTITY, $attributeCode, $attributeParams);
        }

        
        // REGISTER THE FIELD CPF
        $customerCpfAttribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'cpf');
        $customerCpfAttribute->addData([
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            'used_in_forms' => ['adminhtml_customer','checkout_register','customer_account_create','customer_account_edit','adminhtml_checkout'],
        ]);
        $customerCpfAttribute->save();

        
        // REGISTER THE FIELD CNPJ
        $customerCnpjAttribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'cnpj');
        $customerCnpjAttribute->addData([
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            'used_in_forms' => ['adminhtml_customer','checkout_register','customer_account_create','customer_account_edit','adminhtml_checkout'],
        ]);
        $customerCnpjAttribute->save();

        
        // REGISTER THE FIELD RG
        $customerRgAttribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'rg');
        $customerRgAttribute->addData([
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            'used_in_forms' => ['adminhtml_customer','checkout_register','customer_account_create','customer_account_edit','adminhtml_checkout'],
        ]);
        $customerRgAttribute->save();

        // REGISTER THE FIELD SOCIAL NAME
        $customerSocialNameAttribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'socialname');
        $customerSocialNameAttribute->addData([
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            'used_in_forms' => ['adminhtml_customer','checkout_register','customer_account_create','customer_account_edit','adminhtml_checkout'],
        ]);
        $customerSocialNameAttribute->save();

        
        // REGISTER THE FIELD TRADE NAME
        $customerTradeNameAttribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'tradename');
        $customerTradeNameAttribute->addData([
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            'used_in_forms' => ['adminhtml_customer','checkout_register','customer_account_create','customer_account_edit','adminhtml_checkout'],
        ]);
        $customerTradeNameAttribute->save();


        // REGISTER THE FIELD IE
        $customerIeAttribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'ie');
        $customerIeAttribute->addData([
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            'used_in_forms' => ['adminhtml_customer','checkout_register','customer_account_create','customer_account_edit','adminhtml_checkout'],
        ]);
        $customerIeAttribute->save();
        $setup->endSetup();
    }
}