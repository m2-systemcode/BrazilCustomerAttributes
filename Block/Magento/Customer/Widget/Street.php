<?php

namespace SystemCode\BrazilCustomerAttributes\Block\Magento\Customer\Widget;

use SystemCode\BrazilCustomerAttributes\Helper\Data as Helper;
use Magento\Customer\Api\CustomerMetadataInterface;

/**
 *
 * Block to render customer's address attribute
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
class Street extends \Magento\Customer\Block\Widget\AbstractWidget
{

    protected $helper;

    /**
     * Create an instance of the Gender widget
     *
     * @param Helper $helper
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Helper\Address $addressHelper
     * @param CustomerMetadataInterface $customerMetadata
     * @param array $data
     */
    public function __construct(
        Helper $helper,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Helper\Address $addressHelper,
        CustomerMetadataInterface $customerMetadata,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $addressHelper, $customerMetadata, $data);
    }

    /**
     * Initialize block
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('SystemCode_BrazilCustomerAttributes::widget/street.phtml');
    }

    /**
     * Check if gender attribute enabled in system
     * @return bool
     */
    public function getSecondLineNumber()
    {
        return $this->helper->getConfig("brazilcustomerattributes/general/line_number");
    }

    /**
     * Check if gender attribute enabled in system
     * @return bool
     */
    public function getThirdLineNeighborhood()
    {
        return $this->helper->getConfig("brazilcustomerattributes/general/line_neighborhood");
    }

    /**
     * Check if gender attribute enabled in system
     * @return bool
     */
    public function getFourthLineComplement()
    {
        return $this->helper->getConfig("brazilcustomerattributes/general/line_complement");
    }

}
