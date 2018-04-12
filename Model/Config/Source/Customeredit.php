<?php
namespace SystemCode\BrazilCustomerAttributes\Model\Config\Source;

/**
 *
 * Add options to select about customer can edit after account created
 *
 * NOTICE OF LICENSE
 *
 * @category   SystemCode
 * @package    Systemcode_BrazilCustomerAttributes
 * @author     Eduardo Diogo Dias <contato@systemcode.com.br>
 * @copyright  System Code LTDA-ME
 * @license    http://opensource.org/licenses/osl-3.0.php
 */
class Customeredit implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '', 'label' => __('No')],
            ['value' => 'yes', 'label' => __('Yes, except change person type')],
            ['value' => 'yesall', 'label' => __('Yes, and allow change person type')]
        ];
    }
}
