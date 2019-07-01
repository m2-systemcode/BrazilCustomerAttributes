<?php

namespace SystemCode\BrazilCustomerAttributes\Observer;

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
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // copy shipping address street prefix
        $quoteShippingAdress = $observer->getQuote()->getShippingAddress();
        $street_prefix = $quoteShippingAdress->getStreetPrefix();
        $orderShippingAdress = $observer->getOrder()->getShippingAddress();
        $orderShippingAdress->setStreetPrefix($street_prefix)->save();

        // copy billing address street prefix
        $quoteBillingAddress = $observer->getQuote()->getBillingAddress();
        $street_prefix = $quoteBillingAddress->getStreetPrefix();
        $orderBillingAddress = $observer->getOrder()->getBillingAddress();
        $orderBillingAddress->setStreetPrefix($street_prefix)->save();

        return $this;
    }

}