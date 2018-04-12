<?php

namespace Systemcode\BrazilCustomerAttributes\Block\Magento\Customer\Widget;

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
class Streetedit extends \Magento\Customer\Block\Address\Edit
{

    protected $helper;

    /**
     * Create an instance of the Gender widget
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Directory\Helper\Data $directoryHelper
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\App\Cache\Type\Config $configCacheType
     * @param \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory
     * @param \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Api\AddressRepositoryInterface $addressRepository
     * @param \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param Helper $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Directory\Helper\Data $directoryHelper,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\App\Cache\Type\Config $configCacheType,
        \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        Helper $helper,
        array $data = []
    ) {
        $this->helper = $helper;

        parent::__construct(
            $context,
            $directoryHelper,
            $jsonEncoder,
            $configCacheType,
            $regionCollectionFactory,
            $countryCollectionFactory,
            $customerSession,
            $addressRepository,
            $addressDataFactory,
            $currentCustomer,
            $dataObjectHelper,
            $data
        );
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
