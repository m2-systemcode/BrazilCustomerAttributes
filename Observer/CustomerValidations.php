<?php

namespace SystemCode\BrazilCustomerAttributes\Observer;

use \Magento\Framework\Message\ManagerInterface;
use \Magento\Framework\App\RequestInterface;
use \Magento\Framework\Exception\CouldNotSaveException;
use SystemCode\BrazilCustomerAttributes\Helper\Data as Helper;
use \Magento\Customer\Model\Session;

/**
 *
 * Observer to validate customer data depending on the module settings
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
class CustomerValidations implements \Magento\Framework\Event\ObserverInterface
{
    private $_request;
    private $_helper;
    private $_session;

    public function __construct(
        ManagerInterface $messageManager,
        RequestInterface $request,
        Helper $helper,
        Session $session
    ) {
        $this->_request = $request;
        $this->_helper = $helper;
        $this->_session = $session;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $params = $this->_request->getParams();

        if(isset($params["person_type"]) && $params["person_type"]=="cpf"){
            $cpf = (isset($params["cpf"])?$params["cpf"]:"");
            $rg = (isset($params["rg"])?$params["rg"]:"");

            if($cpf!="") {
                if (!$this->_helper->validateCPF($cpf)) {
                    throw new CouldNotSaveException(
                        __("%1 is invalid.", "CPF")
                    );
                }
            }

            if(!$this->_validateInput($cpf, "cpf", "cpf/cpf_show")){
                throw new CouldNotSaveException(
                    __("%1 already in use.", "CPF")
                );
            }

            if(!$this->_validateInput($rg, "rg", "cpf/rg_show")){
                throw new CouldNotSaveException(
                    __("%1 already in use.", "RG")
                );
            }

        }else if(isset($params["person_type"]) && $params["person_type"]=="cnpj"){
            $cnpj = (isset($params["cnpj"])?$params["cnpj"]:"");
            $ie = (isset($params["ie"])?$params["ie"]:"");
            $socialName = (isset($params["socialname"])?$params["socialname"]:"");
            $tradeName = (isset($params["tradename"])?$params["tradename"]:"");

            if($cnpj!=""){
                if(!$this->_helper->validateCNPJ($cnpj)){
                    throw new CouldNotSaveException(
                        __("%1 is invalid.", "CNPJ")
                    );
                }
            }

            if(!$this->_validateInput($cnpj, "cnpj", "cnpj/cnpj_show")){
                throw new CouldNotSaveException(
                    __("%1 already in use.", "CNPJ")
                );
            }

            if(!$this->_validateInput($ie, "ie", "cnpj/ie_show")){
                throw new CouldNotSaveException(
                    __("%1 already in use.", "ie")
                );
            }

            if(!$this->_validateInput($socialName, "socialname", "cnpj/socialname_show")){
                throw new CouldNotSaveException(
                    __("%1 already in use.", "Social Name")
                );
            }

            if(!$this->_validateInput($tradeName, "tradename", "cnpj/tradename_show")){
                throw new CouldNotSaveException(
                    __("%1 already in use.", "Trade Name")
                );
            }
        }
    }

    protected function _validateInput($value, $fieldName, $path){
        $show = $this->_helper->getConfig("brazilcustomerattributes/".$path);
        if($show == "req" || $show == "requni"){
            if($value == ""){
                return false;
            }
            //verify if is unique
            if($show == "requni"){
                if($this->_session->getCustomer()->getId()!="" && $this->_session->getCustomer()->getData($fieldName) == $value){ //verifico se é ele mesmo que utiliza
                    return true;
                }

                //check if field already being used
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

                $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->getCollection();
                $customerObj->addFieldToFilter($fieldName, $value);


                foreach ($customerObj as $customer){
                    if($customer->getCreatedAt()){
                        return true;
                    }
                    return false;
                }
            }
        }
        return true;
    }
}