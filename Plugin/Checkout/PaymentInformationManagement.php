<?php

namespace SystemCode\BrazilCustomerAttributes\Plugin\Checkout;

use Magento\Quote\Api\BillingAddressManagementInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Checkout\Model\PaymentInformationManagement as CorePaymentInformationManagement;

/**
 *
 * Assign billing address custom attributes
 *
 * NOTICE OF LICENSE
 *
 * @category   SystemCode
 * @package    Systemcode_BrazilCustomerAttributes
 * @author     Eduardo Diogo Dias <contato@systemcode.com.br>
 * @copyright  System Code LTDA-ME
 * @license    http://opensource.org/licenses/osl-3.0.php
 */

class PaymentInformationManagement {
    /**
     * @var BillingAddressManagementInterface
     * @deprecated 100.2.0 This call was substituted to eliminate extra quote::save call
     *
     * TODO: Shipping method still use similar method to assign, but on billing address this method is deprecated
     */
    protected $billingAddressManagement;

    /**
     * @param BillingAddressManagementInterface $billingAddressManagement
     * @codeCoverageIgnore
     */
    public function __construct(
        BillingAddressManagementInterface $billingAddressManagement
    ) {
        $this->billingAddressManagement = $billingAddressManagement;
    }

    /**
     * {@inheritDoc}
     */
    public function beforeSavePaymentInformationAndPlaceOrder(
        CorePaymentInformationManagement $subject,
        $cartId,
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    ) {
        if($billingAddress){
            $this->billingAddressManagement->assign($cartId, $billingAddress);
        }
    }
}