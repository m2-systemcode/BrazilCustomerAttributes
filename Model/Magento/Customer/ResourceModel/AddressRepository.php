<?php
namespace SystemCode\BrazilCustomerAttributes\Model\Magento\Customer\ResourceModel;
use SystemCode\BrazilCustomerAttributes\Helper\Data as Helper;

/**
 *
 * Add custom validations on address
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
class AddressRepository extends \Magento\Customer\Model\ResourceModel\AddressRepository
{
    protected $helper;

    /**
     * @param \Magento\Customer\Model\AddressFactory $addressFactory
     * @param \Magento\Customer\Model\AddressRegistry $addressRegistry
     * @param \Magento\Customer\Model\CustomerRegistry $customerRegistry
     * @param \Magento\Customer\Model\ResourceModel\Address $addressResourceModel
     * @param \Magento\Directory\Helper\Data $directoryData
     * @param \Magento\Customer\Api\Data\AddressSearchResultsInterfaceFactory $addressSearchResultsFactory
     * @param \Magento\Customer\Model\ResourceModel\Address\CollectionFactory $addressCollectionFactory
     * @param \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor
     */
    public function __construct(
        Helper $helper,
        \Magento\Customer\Model\AddressFactory $addressFactory,
        \Magento\Customer\Model\AddressRegistry $addressRegistry,
        \Magento\Customer\Model\CustomerRegistry $customerRegistry,
        \Magento\Customer\Model\ResourceModel\Address $addressResourceModel,
        \Magento\Directory\Helper\Data $directoryData,
        \Magento\Customer\Api\Data\AddressSearchResultsInterfaceFactory $addressSearchResultsFactory,
        \Magento\Customer\Model\ResourceModel\Address\CollectionFactory $addressCollectionFactory,
        \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor
    ) {
        $this->helper = $helper;
        parent::__construct($addressFactory, $addressRegistry, $customerRegistry, $addressResourceModel, $directoryData, $addressSearchResultsFactory, $addressCollectionFactory, $extensionAttributesJoinProcessor);
    }

    /**
     * Validate Customer Addresses attribute values.
     *
     * @param CustomerAddressModel $customerAddressModel the model to validate
     * @return InputException
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function _validate(CustomerAddressModel $customerAddressModel)
    {
        $exception = new InputException();
        if ($customerAddressModel->getShouldIgnoreValidation()) {
            return $exception;
        }

        if (!\Zend_Validate::is($customerAddressModel->getFirstname(), 'NotEmpty')) {
            $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'firstname']));
        }

        if (!\Zend_Validate::is($customerAddressModel->getLastname(), 'NotEmpty')) {
            $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'lastname']));
        }

        if (!\Zend_Validate::is($customerAddressModel->getStreetLine(1), 'NotEmpty')) {
            $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'street']));
        }

        /* Custom Street Validations */
        if ($this->helper->getConfig("brazilcustomerattributes/general/line_number") &&
            !\Zend_Validate::is($customerAddressModel->getStreetLine(2), 'NotEmpty')) {
            $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'number']));
        }

        if ($this->helper->getConfig("brazilcustomerattributes/general/line_neighborhood") &&
            !\Zend_Validate::is($customerAddressModel->getStreetLine(3), 'NotEmpty')) {
            $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'neighborhood']));
        }

        if ($this->helper->getConfig("brazilcustomerattributes/general/line_complement") &&
            !\Zend_Validate::is($customerAddressModel->getStreetLine(4), 'NotEmpty')) {
            $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'complement']));
        }
        $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'complement']));


        if (!\Zend_Validate::is($customerAddressModel->getCity(), 'NotEmpty')) {
            $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'city']));
        }

        if (!\Zend_Validate::is($customerAddressModel->getTelephone(), 'NotEmpty')) {
            $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'telephone']));
        }

        $havingOptionalZip = $this->directoryData->getCountriesWithOptionalZip();
        if (!in_array($customerAddressModel->getCountryId(), $havingOptionalZip)
            && !\Zend_Validate::is($customerAddressModel->getPostcode(), 'NotEmpty')
        ) {
            $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'postcode']));
        }

        if (!\Zend_Validate::is($customerAddressModel->getCountryId(), 'NotEmpty')) {
            $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'countryId']));
        }

        if ($this->directoryData->isRegionRequired($customerAddressModel->getCountryId())) {
            $regionCollection = $customerAddressModel->getCountryModel()->getRegionCollection();
            if (!$regionCollection->count() && empty($customerAddressModel->getRegion())) {
                $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'region']));
            } elseif (
                $regionCollection->count()
                && !in_array(
                    $customerAddressModel->getRegionId(),
                    array_column($regionCollection->getData(), 'region_id')
                )
            ) {
                $exception->addError(__('%fieldName is a required field.', ['fieldName' => 'regionId']));
            }
        }
        return $exception;
    }
}