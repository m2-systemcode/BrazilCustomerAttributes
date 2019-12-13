<?php
namespace SystemCode\BrazilCustomerAttributes\Plugin\Quote;

use Psr\Log\LoggerInterface;
use Magento\Quote\Model\BillingAddressManagement as CoreBillingAddressManagement;
use Magento\Quote\Api\Data\AddressInterface;

/**
 * Copy street prefix from checkout billing address to customer/order address  *
 *
 * NOTICE OF LICENSE
 *
 * @category  SystemCode
 * @package   Systemcode_BrazilCustomerAttributes
 * @author    Eduardo Diogo Dias <contato@systemcode.com.br>
 * @copyright System Code LTDA-ME
 * @license   http://opensource.org/licenses/osl-3.0.php
 */

class BillingAddressManagement
{

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Construct
     *
     * BillingAddressManagement constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * Before Assign
     *
     * @param CoreBillingAddressManagement $subject
     * @param $cartId
     * @param AddressInterface $address
     * @param bool $useForShipping
     */
    public function beforeAssign(
        CoreBillingAddressManagement $subject,
        $cartId,
        AddressInterface $address,
        $useForShipping = false
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