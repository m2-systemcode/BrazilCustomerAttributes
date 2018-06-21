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
class Streetprefix extends \Magento\Customer\Block\Address\Edit
{

    protected $helper;

    protected $streetprefix;

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
        \SystemCode\BrazilCustomerAttributes\Model\Config\Source\Streetprefix $streetprefix,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->streetprefix = $streetprefix;

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
        $this->setTemplate('SystemCode_BrazilCustomerAttributes::widget/streetprefix.phtml');
    }

    /**
     * Check if street prefix is enabled
     * @return bool
     */
    public function getIsEnabled()
    {
        return $this->helper->getConfig("brazilcustomerattributes/general/prefix_enabled");
    }

    /**
     * Check if street prefix is enabled
     * @return array
     */
    public function getStreetPrefixOptions()
    {
        return $this->streetprefix->getAllOptions();
    }


    /**
     * Check if address already has street prefix
     * @return value
     */
    public function getCurrentStreetPrefix()
    {
        if(null != ($current = $this->getAddress()->getCustomAttribute('street_prefix'))){
            return $current->getValue();
        }
        return;
    }

}
