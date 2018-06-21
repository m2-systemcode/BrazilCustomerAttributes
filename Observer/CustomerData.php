<?php

namespace SystemCode\BrazilCustomerAttributes\Observer;

use \Magento\Framework\Message\ManagerInterface;
use \Magento\Framework\App\RequestInterface;
use SystemCode\BrazilCustomerAttributes\Helper\Data as Helper;
use \Magento\Customer\Model\Session;

/**
 *
 * Observer to set customer data depending on the module settings
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
class CustomerData implements \Magento\Framework\Event\ObserverInterface
{
    private $_request;
    private $_helper;
    private $_customer;

    public function __construct(
        ManagerInterface $messageManager,
        RequestInterface $request,
        Helper $helper,
        Session $session
    ) {
        $this->_request = $request;
        $this->_helper = $helper;
        $this->_customer = $session->getCustomer();
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $params = $this->_request->getParams();
        $customer = $observer->getCustomer();

        if($customer->getId()){
            if(isset($params["person_type"]) && $params["person_type"]=="cpf"){
                $groupId = $this->_helper->getConfig("brazilcustomerattributes/general/customer_group_cpf");

                $customer->setCnpj();
                $customer->setSocialname();
                $customer->setIe();
            }else if(isset($params["person_type"]) && $params["person_type"]=="cnpj"){
                $groupId = $this->_helper->getConfig("brazilcustomerattributes/general/customer_group_cnpj");

                $customer->setCpf();
                $customer->setRg();
            }

            if(isset($params["cpf"]) && $params["cpf"]!=""){
                $document = $params["cpf"];
            }else if(isset($params["cnpj"]) && $params["cnpj"]!=""){
                $document = $params["cnpj"];
            }

            if(isset($groupId) && $groupId!=""){
                $customer->setGroupId($groupId);
            }

            if(isset($document) && $this->_helper->getConfig("brazilcustomerattributes/general/copy_taxvat")){
                $customer->setTaxvat($document);
            }

            $customer->save();
        }

    }
}
