<?php

namespace SystemCode\BrazilCustomerAttributes\Observer;

use \Magento\Customer\Api\AddressRepositoryInterface;

/**
 *
 * Observer to copy quote fields to order
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
class QuoteToOrder implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var AddressRepositoryInterface
     */
    protected $addressRepository;

    /**
     * QuoteToOrder constructor.
     * @param AddressRepositoryInterface $addressRepository
     */
    public function __construct(
        AddressRepositoryInterface $addressRepository
    ) {
        $this->addressRepository = $addressRepository;
    }

    /**
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // copy shipping address street prefix
        $quoteShippingAdress = $observer->getQuote()->getShippingAddress();
        $street_prefix = $quoteShippingAdress->getStreetPrefix();
        if(isset($street_prefix)) {
            $orderShippingAdress = $observer->getOrder()->getShippingAddress();
            $orderShippingAdress->setStreetPrefix($street_prefix)->save();

            // copy shipping address street prefix to customer
            if ($addressId = $orderShippingAdress->getCustomerAddressId()) {
                $this->updateCustomerAddress($addressId, $street_prefix);
            }
        }

        // copy billing address street prefix
        $quoteBillingAddress = $observer->getQuote()->getBillingAddress();
        $street_prefix = $quoteBillingAddress->getStreetPrefix();
        if(isset($street_prefix)) {
            $orderBillingAddress = $observer->getOrder()->getBillingAddress();
            $orderBillingAddress->setStreetPrefix($street_prefix)->save();

            // copy billing address street prefix to customer
            if ($addressId = $orderBillingAddress->getCustomerAddressId()) {
                $this->updateCustomerAddress($addressId, $street_prefix);
            }
        }

        return $this;
    }

    /**
     * Update street prefix on customer address
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    protected function updateCustomerAddress($addressId, $streetPrefix) {
        $address = $this->addressRepository->getById($addressId);
        $address->setCustomAttribute('street_prefix', $streetPrefix);
        $this->addressRepository->save($address);
    }

}