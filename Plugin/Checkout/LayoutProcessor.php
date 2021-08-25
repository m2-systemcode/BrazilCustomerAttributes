<?php

namespace SystemCode\BrazilCustomerAttributes\Plugin\Checkout;

use SystemCode\BrazilCustomerAttributes\Helper\Data as Helper;
use Magento\Checkout\Helper\Data;

/**
 * Model to add label each address line
 *
 * NOTICE OF LICENSE
 *
 * @category  SystemCode
 * @package   Systemcode_BrazilCustomerAttributes
 * @author    Eduardo Diogo Dias <contato@systemcode.com.br>
 * @copyright System Code LTDA-ME
 * @license   http://opensource.org/licenses/osl-3.0.php
 */
class LayoutProcessor
{

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var \SystemCode\BrazilCustomerAttributes\Model\Config\Source\Streetprefix
     */
    protected $streetprefix;

    /**
     * @var Array
     */
    protected $streetprefixoptions;

    /**
     * LayoutProcessorPlugin constructor.
     *
     * @param Helper                                                                $helper
     * @param \SystemCode\BrazilCustomerAttributes\Model\Config\Source\Streetprefix $streetprefix
     */
    public function __construct(
        Helper $helper,
        \SystemCode\BrazilCustomerAttributes\Model\Config\Source\Streetprefix $streetprefix,
        Data $checkoutDataHelper
    ) {
        $this->helper = $helper;
        $this->streetprefix = $streetprefix;
        $this->checkoutDataHelper = $checkoutDataHelper;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array                                            $jsLayout
     * @return array
     */

    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
    ) {
        $numStreetLines = $this->helper->getConfig("customer/address/street_lines");
        $this->setStreetPrefixOptions();
        $jsLayout = $this->getShippingFormFields($jsLayout, $numStreetLines);

        if ($this->checkoutDataHelper->isDisplayBillingOnPaymentMethodAvailable()) {
            $jsLayout = $this->getBillingFormFields($jsLayout, $numStreetLines);
        } else {
            $jsLayout = $this->getBillingFormFieldsOnPage($jsLayout, $numStreetLines);
        }

        return $jsLayout;
    }

    public function setStreetPrefixOptions()
    {
        $this->streetprefixoptions = [];

        if($this->helper->getConfig("brazilcustomerattributes/general/prefix_enabled")) {
            foreach ($this->streetprefix->getAllOptions() as $op) {
                $this->streetprefixoptions[] = [
                    'label' => $op["label"],
                    'value' => $op["value"]
                ];
            }
        }
    }

    public function getShippingFormFields($jsLayout, $numStreetLines)
    {
        $shippingAddressFieldsetChild = $jsLayout['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];

        // Street Label
        $shippingAddressFieldsetChild['street']['label'] = '';
        $shippingAddressFieldsetChild['street']['required'] = false;

        // Street Line 0
        $shippingAddressFieldsetChild['street']['children'][0]['label'] = __('Address');

        $shippingAddressFieldsetChild['street']['children'][0]['validation'] = [
            'required-entry' => true,
            'min_text_len‌​gth' => 1,
            'max_text_length' => 255
        ];

        // Street Line 1
        if($this->helper->getConfig("brazilcustomerattributes/general/line_number")
                && $numStreetLines >= 2) {
            $shippingAddressFieldsetChild['street']['children'][1]['label'] = __('Number');
            $shippingAddressFieldsetChild['street']['children'][1]['validation'] = [
                'required-entry' => true,
                'min_text_len‌​gth' => 1,
                'max_text_length' => 255
            ];
        }

        // Street Line 2
        if($this->helper->getConfig("brazilcustomerattributes/general/line_neighborhood")
                && $numStreetLines >= 3) {
            $shippingAddressFieldsetChild['street']['children'][2]['label'] = __('Neighborhood');
            $shippingAddressFieldsetChild['street']['children'][2]['validation'] = [
                'required-entry' => true,
                'min_text_len‌​gth' => 1,
                'max_text_length' => 255
            ];
        }

        // Street Line 3
        if($this->helper->getConfig("brazilcustomerattributes/general/line_complement")
                && $numStreetLines == 4) {
            $shippingAddressFieldsetChild['street']['children'][3]['label'] = __('Complement');
        }

        // Street Prefix
        if($this->helper->getConfig("brazilcustomerattributes/general/prefix_enabled")) {
            $shippingAddressFieldsetChild['street_prefix'] = [
                'component' => 'Magento_Ui/js/form/element/select',
                'config' => [
                    'customScope' => 'shippingAddress.custom_attributes',
                    'template' => 'ui/form/field',
                    'options' => $this->streetprefixoptions,
                    'id' => 'street-prefix'
                ],
                'dataScope' => 'shippingAddress.custom_attributes.street_prefix',
                'label' => __('Street Prefix'),
                'provider' => 'checkoutProvider',
                'visible' => true,
                'validation' => [
                    'required-entry' => true,
                ],
                'sortOrder' => 65,
                'id' => 'street-prefix'
            ];
        }

        // Company
        $shippingAddressFieldsetChild['company']['sortOrder'] = 118;

        // Zipcode
        $shippingAddressFieldsetChild['postcode']['sortOrder'] = 40;
        $shippingAddressFieldsetChild['postcode']['component'] =
            'SystemCode_BrazilCustomerAttributes/js/shipping-address/address-renderer/zip-code';
        $shippingAddressFieldsetChild['postcode']['config']['elementTmpl'] =
            'SystemCode_BrazilCustomerAttributes/shipping-address/address-renderer/zip-code';

        // Telephone
        $shippingAddressFieldsetChild['telephone']['component'] =
            'SystemCode_BrazilCustomerAttributes/js/shipping-address/address-renderer/telephone';

        // Fax
        $shippingAddressFieldsetChild['fax']['component'] =
            'SystemCode_BrazilCustomerAttributes/js/shipping-address/address-renderer/telephone';

        $jsLayout['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children'] = $shippingAddressFieldsetChild;

        return $jsLayout;
    }

    public function getBillingFormFields($jsLayout, $numStreetLines)
    {
        if(isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
            ['children']['payment']['children']['payments-list'])
        ) {
            $paymentForms = $jsLayout['components']['checkout']['children']['steps']['children']
            ['billing-step']['children']['payment']['children']
            ['payments-list']['children'];

            foreach ($paymentForms as $paymentMethodForm => $paymentMethodValue) {

                $paymentMethodCode = str_replace('-form', '', $paymentMethodForm);

                if (!isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
                    ['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form'])
                ) {
                    continue;
                }

                $paymentFormChildren = $jsLayout['components']['checkout']['children']['steps']['children']
                    ['billing-step']['children']['payment']['children']['payments-list']['children']
                    [$paymentMethodCode . '-form']['children']['form-fields']['children'];

                // Street Label
                $paymentFormChildren['street']['label'] = '';
                $paymentFormChildren['street']['required'] = false;

                // Street Line 0
                $paymentFormChildren['street']['children'][0]['label'] = __('Address');

                // Street Line 1
                if($this->helper->getConfig("brazilcustomerattributes/general/line_number")
                        && $numStreetLines >= 2) {
                    $paymentFormChildren['street']['children'][1]['label'] = __('Number');
                    $paymentFormChildren['street']['children'][1]['validation'] = [
                        'required-entry' => true,
                        'min_text_len‌​gth' => 1,
                        'max_text_length' => 255
                    ];
                }

                // Street Line 2
                if($this->helper->getConfig("brazilcustomerattributes/general/line_neighborhood")
                        && $numStreetLines >= 3) {
                    $paymentFormChildren['street']['children'][2]['label'] = __('Neighborhood');
                    $paymentFormChildren['street']['children'][2]['validation'] = [
                        'required-entry' => true,
                        'min_text_len‌​gth' => 1,
                        'max_text_length' => 255
                    ];
                }

                // Street Line 3
                if($this->helper->getConfig("brazilcustomerattributes/general/line_complement")
                        && $numStreetLines == 4) {
                    $paymentFormChildren['street']['children'][3]['label'] = __('Complement');
                }

                // Street Prefix
                if($this->helper->getConfig("brazilcustomerattributes/general/prefix_enabled")) {
                    $paymentFormChildren['street_prefix'] = [
                        'component' => 'Magento_Ui/js/form/element/select',
                        'config' => [
                            'customScope' => 'billingAddress' . $paymentMethodCode . '.custom_attributes',
                            'template' => 'ui/form/field',
                            'options' => $this->streetprefixoptions,
                            'id' => 'street-prefix'
                        ],
                        'dataScope' => 'billingAddress' . $paymentMethodCode . '.custom_attributes.street_prefix',
                        'label' => __('Street Prefix'),
                        'provider' => 'checkoutProvider',
                        'visible' => true,
                        'validation' => [
                            'required-entry' => true,
                        ],
                        'sortOrder' => 65,
                        'id' => 'street-prefix'
                    ];
                }

                // Company
                $paymentFormChildren['company']['sortOrder'] = 118;

                // Zipcode
                $paymentFormChildren['postcode']['sortOrder'] = 40;
                $paymentFormChildren['postcode']['component'] =
                    'SystemCode_BrazilCustomerAttributes/js/shipping-address/address-renderer/zip-code';
                $paymentFormChildren['postcode']['config']['elementTmpl'] =
                    'SystemCode_BrazilCustomerAttributes/shipping-address/address-renderer/zip-code';

                // Telephone
                $paymentFormChildren['telephone']['component'] =
                    'SystemCode_BrazilCustomerAttributes/js/shipping-address/address-renderer/telephone';

                // Fax
                $paymentFormChildren['fax']['component'] =
                    'SystemCode_BrazilCustomerAttributes/js/shipping-address/address-renderer/telephone';

                $jsLayout['components']['checkout']['children']['steps']['children']
                    ['billing-step']['children']['payment']['children']['payments-list']['children']
                    [$paymentMethodCode . '-form']['children']['form-fields']['children'] = $paymentFormChildren;
            }
        }

        return $jsLayout;
    }

    public function getBillingFormFieldsOnPage($jsLayout, $numStreetLines)
    {
        if(isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
            ['children']['payment']['children']['afterMethods']['children']['billing-address-form'])
        ) {
                $paymentFormChildren = $jsLayout['components']['checkout']['children']['steps']['children']
                    ['billing-step']['children']['payment']['children']['afterMethods']['children']['billing-address-form']['children']['form-fields']['children'];

                // Street Label
                $paymentFormChildren['street']['label'] = '';
                $paymentFormChildren['street']['required'] = false;

                // Street Line 0
                $paymentFormChildren['street']['children'][0]['label'] = __('Address');

                // Street Line 1
                if($this->helper->getConfig("brazilcustomerattributes/general/line_number")
                        && $numStreetLines >= 2) {
                    $paymentFormChildren['street']['children'][1]['label'] = __('Number');
                    $paymentFormChildren['street']['children'][1]['validation'] = [
                        'required-entry' => true,
                        'min_text_len‌​gth' => 1,
                        'max_text_length' => 255
                    ];
                }

                // Street Line 2
                if($this->helper->getConfig("brazilcustomerattributes/general/line_neighborhood")
                        && $numStreetLines >= 3) {
                    $paymentFormChildren['street']['children'][2]['label'] = __('Neighborhood');
                    $paymentFormChildren['street']['children'][2]['validation'] = [
                        'required-entry' => true,
                        'min_text_len‌​gth' => 1,
                        'max_text_length' => 255
                    ];
                }

                // Street Line 3
                if($this->helper->getConfig("brazilcustomerattributes/general/line_complement")
                        && $numStreetLines == 4) {
                    $paymentFormChildren['street']['children'][3]['label'] = __('Complement');
                }

                // Street Prefix
                if($this->helper->getConfig("brazilcustomerattributes/general/prefix_enabled")) {
                    $paymentFormChildren['street_prefix'] = [
                        'component' => 'Magento_Ui/js/form/element/select',
                        'config' => [
                            'customScope' => 'billingAddress.shared.custom_attributes',
                            'template' => 'ui/form/field',
                            'options' => $this->streetprefixoptions,
                            'id' => 'street-prefix'
                        ],
                        'dataScope' => 'billingAddress.custom_attributes.street_prefix',
                        'label' => __('Street Prefix'),
                        'provider' => 'checkoutProvider',
                        'visible' => true,
                        'validation' => [
                            'required-entry' => true,
                        ],
                        'sortOrder' => 65,
                        'id' => 'street-prefix'
                    ];
                }

                // Company
                $paymentFormChildren['company']['sortOrder'] = 118;

                // Zipcode
                $paymentFormChildren['postcode']['sortOrder'] = 40;
                $paymentFormChildren['postcode']['component'] =
                    'SystemCode_BrazilCustomerAttributes/js/shipping-address/address-renderer/zip-code';
                $paymentFormChildren['postcode']['config']['elementTmpl'] =
                    'SystemCode_BrazilCustomerAttributes/shipping-address/address-renderer/zip-code';

                // Telephone
                $paymentFormChildren['telephone']['component'] =
                    'SystemCode_BrazilCustomerAttributes/js/shipping-address/address-renderer/telephone';

                // Fax
                $paymentFormChildren['fax']['component'] =
                    'SystemCode_BrazilCustomerAttributes/js/shipping-address/address-renderer/telephone';

                $jsLayout['components']['checkout']['children']['steps']['children']
                    ['billing-step']['children']['payment']['children']['afterMethods']['children']['billing-address-form']['children']['form-fields']['children'] = $paymentFormChildren;
            
        }

        return $jsLayout;
    }    
}
