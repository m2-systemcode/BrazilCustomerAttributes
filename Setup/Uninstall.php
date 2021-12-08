<?php

namespace SystemCode\BrazilCustomerAttributes\Setup;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

use Magento\Customer\Model\Customer;

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
class Uninstall implements UninstallInterface
{
    private $eavSetupFactory;

    public function __construct(
        EavSetupFactory $eavSetupFactory
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $connection = $setup->getConnection();
        $eavSetup = $this->eavSetupFactory->create();

        $attributes = [
            'cpf',
            'cnpj',
            'rg',
            'socialname',
            'tradename',
            'ie'
        ];

        foreach ($attributes as $attribute) {
            $eavSetup->removeAttribute(Customer::ENTITY, $attribute);
        }

        $eavSetup->removeAttribute('customer_address', 'street_prefix');

        $connection->dropColumn($setup->getTable('sales_order_address'), 'street_prefix');
        $connection->dropColumn($setup->getTable('quote_address'), 'street_prefix');

        $setup->endSetup();
    }
}
