<?php
namespace SystemCode\BrazilCustomerAttributes\Model\Magento\Checkout;

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
     * @var \Magento\Quote\Api\BillingAddressManagementInterface
     * @deprecated 100.2.0 This call was substituted to eliminate extra quote::save call
     *
     * TODO: Shipping method still use similar method to assign, but on billing address this method is deprecated
     */
    protected $billingAddressManagement;

    /**
     * @param \Magento\Quote\Api\BillingAddressManagementInterface $billingAddressManagement
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Quote\Api\BillingAddressManagementInterface $billingAddressManagement
    ) {
        $this->billingAddressManagement = $billingAddressManagement;
    }

    /**
     * {@inheritDoc}
     */
    public function beforeSavePaymentInformationAndPlaceOrder(
        \Magento\Checkout\Model\PaymentInformationManagement $subject,
        $cartId,
        \Magento\Quote\Api\Data\PaymentInterface $paymentMethod,
        \Magento\Quote\Api\Data\AddressInterface $billingAddress = null
    ) {
        if($billingAddress){
            $this->billingAddressManagement->assign($cartId, $billingAddress);
        }
    }
}