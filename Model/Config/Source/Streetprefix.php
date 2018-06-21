<?php

namespace SystemCode\BrazilCustomerAttributes\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;

use Magento\Framework\Serialize\SerializerInterface;
use SystemCode\BrazilCustomerAttributes\Helper\Data as Helper;

/**
 *
 * Add prefix for customer address street
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
class Streetprefix extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource {

    /**
     * @var OptionFactory
     */
    protected $optionFactory;

    /**
     * Json Serializer
     *
     * @var SerializerInterface
     */
    protected $serializer;

    protected $helper;

    public function __construct(
        Helper $helper,
        SerializerInterface $serializer
    )
    {
        $this->helper = $helper;
        $this->serializer = $serializer;
    }

    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if($this->helper->getConfig('brazilcustomerattributes/general/prefix_enabled')){
            $options = $this->helper->getConfig('brazilcustomerattributes/general/prefix_options');
            $optionsArr = $this->serializer->unserialize($options);

            $this->_options[] =  ['label' => __('Please select a street prefix.'), 'value' => ''];
            foreach ($optionsArr as $op){
                $this->_options[] =  ['label' => $op["prefix_options"], 'value' => $op["prefix_options"]];
            }

            return $this->_options;
        }
        return [];
    }
}
