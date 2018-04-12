<?php
namespace SystemCode\BrazilCustomerAttributes\Model\Config\Source;

/**
 *
 * Add config for input value config validations
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
class Nooptrequn implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '', 'label' => __('No')],
            ['value' => 'opt', 'label' => __('Optional')],
            ['value' => 'req', 'label' => __('Required')],
            ['value' => 'optuni', 'label' => __('Optional and Unique')],
            ['value' => 'requni', 'label' => __('Required and Unique')]
        ];
    }
}
