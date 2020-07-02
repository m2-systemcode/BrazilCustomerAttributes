<?php

namespace SystemCode\BrazilCustomerAttributes\Plugin\Checkout;

use SystemCode\BrazilCustomerAttributes\Helper\Data as Helper;

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
        \SystemCode\BrazilCustomerAttributes\Model\Config\Source\Streetprefix $streetprefix
    ) {
        $this->helper = $helper;
        $this->streetprefix = $streetprefix;
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
        $jsLayout = $this->getBillingFormFields($jsLayout, $numStreetLines);

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
        // Street Label
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['label'] = '';
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['required'] = false;

        // Street Line 0
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['children'][0]['label'] = __('Address');

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['children'][0]['validation'] = ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 255];

        // Street Line 1
        if($this->helper->getConfig("brazilcustomerattributes/general/line_number") && $numStreetLines >= 2) {
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['children'][1]['label'] = __('Number');
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['children'][1]['validation'] = ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 255];
        }

        // Street Line 2
        if($this->helper->getConfig("brazilcustomerattributes/general/line_neighborhood") && $numStreetLines >= 3) {
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['children'][2]['label'] = __('Neighborhood');
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['children'][2]['validation'] = ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 255];
        }

        // Street Line 3
        if($this->helper->getConfig("brazilcustomerattributes/general/line_complement") && $numStreetLines == 4) {
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['children'][3]['label'] = __('Complement');
        }

        // Street Prefix
        if($this->helper->getConfig("brazilcustomerattributes/general/prefix_enabled")) {
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['street_prefix'] = [
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
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['company']['sortOrder'] = 118;

        // Zipcode
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['postcode']['sortOrder'] = 40;
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['postcode']['component'] = 'SystemCode_BrazilCustomerAttributes/js/shipping-address/address-renderer/zip-code';
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['postcode']['config']['elementTmpl'] = 'SystemCode_BrazilCustomerAttributes/shipping-address/address-renderer/zip-code';

        // Telephone
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['telephone']['component'] = 'SystemCode_BrazilCustomerAttributes/js/shipping-address/address-renderer/telephone';

        // Fax
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['fax']['component'] = 'SystemCode_BrazilCustomerAttributes/js/shipping-address/address-renderer/telephone';

        return $jsLayout;
    }

    public function getBillingFormFields($jsLayout, $numStreetLines)
    {
        if(isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list'])
        ) {

            $paymentForms = $jsLayout['components']['checkout']['children']['steps']['children']
            ['billing-step']['children']['payment']['children']
            ['payments-list']['children'];

            foreach ($paymentForms as $paymentMethodForm => $paymentMethodValue) {

                $paymentMethodCode = str_replace('-form', '', $paymentMethodForm);

                if (!isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form'])
                ) {
                    continue;
                }

                // Street Label
                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
                ['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']
                ['children']['form-fields']['children']['street']['label'] = '';
                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
                ['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']
                ['children']['form-fields']['children']['street']['required'] = false;

                // Street Line 0
                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
                ['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']
                ['children']['form-fields']['children']['street']['children'][0]['label'] = __('Address');

                // Street Line 1
                if($this->helper->getConfig("brazilcustomerattributes/general/line_number") && $numStreetLines >= 2) {
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
                    ['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']
                    ['children']['form-fields']['children']['street']['children'][1]['label'] = __('Number');
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
                    ['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']
                    ['children']['form-fields']['children']['street']['children'][1]['validation'] = ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 255];
                }

                // Street Line 2
                if($this->helper->getConfig("brazilcustomerattributes/general/line_neighborhood") && $numStreetLines >= 3) {
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
                    ['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']
                    ['children']['form-fields']['children']['street']['children'][2]['label'] = __('Neighborhood');
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
                    ['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']
                    ['children']['form-fields']['children']['street']['children'][2]['validation'] = ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 255];
                }

                // Street Line 3
                if($this->helper->getConfig("brazilcustomerattributes/general/line_complement") && $numStreetLines == 4) {
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
                    ['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']
                    ['children']['form-fields']['children']['street']['children'][3]['label'] = __('Complement');
                }

                // Street Prefix
                if($this->helper->getConfig("brazilcustomerattributes/general/prefix_enabled")) {
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                    ['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']['children']
                    ['form-fields']['children']['street_prefix'] = [
                        'component' => 'Magento_Ui/js/form/element/select',
                        'config' => [
                            'customScope' => 'billingAddress' . $paymentMethodCode . '.custom_attributes',
                            'template' => 'ui/form/field',
                            'options' => $this->streetprefixoptions,
                            'id' => 'street-prefix'
                        ],
                        'dataScope' => 'billingAddress' . $paymentMethodCode . '.custom_attributes.street_prefix', //billingAddresscheckmo.city
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
                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']['children']
                ['form-fields']['children']['company']['sortOrder'] = 118;

                // Zipcode
                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']['children']
                ['form-fields']['children']['postcode']['sortOrder'] = 40;
                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']['children']
                ['form-fields']['children']['postcode']['component'] = 'SystemCode_BrazilCustomerAttributes/js/shipping-address/address-renderer/zip-code';
                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']['children']
                ['form-fields']['children']['postcode']['config']['elementTmpl'] = 'SystemCode_BrazilCustomerAttributes/shipping-address/address-renderer/zip-code';

                // Telephone
                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']['children']
                ['form-fields']['children']['telephone']['component'] = 'SystemCode_BrazilCustomerAttributes/js/shipping-address/address-renderer/telephone';

                // Fax
                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form']['children']
                ['form-fields']['children']['fax']['component'] = 'SystemCode_BrazilCustomerAttributes/js/shipping-address/address-renderer/telephone';
            }
        }

        return $jsLayout;
    }
}