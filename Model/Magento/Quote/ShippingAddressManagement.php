<?php
namespace SystemCode\BrazilCustomerAttributes\Model\Magento\Quote;

/**
 *
 * Copy street prefix from checkout shipping address to customer/order address
 *
 * NOTICE OF LICENSE
 *
 * @category   SystemCode
 * @package    Systemcode_BrazilCustomerAttributes
 * @author     Eduardo Diogo Dias <contato@systemcode.com.br>
 * @copyright  System Code LTDA-ME
 * @license    http://opensource.org/licenses/osl-3.0.php
 */
class ShippingAddressManagement
{

    protected $logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function beforeAssign(
        \Magento\Quote\Model\ShippingAddressManagement $subject,
        $cartId,
        \Magento\Quote\Api\Data\AddressInterface $address
    ) {
        $extAttributes = $address->getExtensionAttributes();
        if (!empty($extAttributes)) {

            try {
                $address->setStreetPrefix($extAttributes->getStreetPrefix());
            } catch (\Exception $e) {
                $this->logger->critical($e->getMessage());
            }
        }
    }
}