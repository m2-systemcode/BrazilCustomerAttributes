<?php

namespace SystemCode\BrazilCustomerAttributes\Controller\Consult;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use SystemCode\BrazilCustomerAttributes\Model\Address\ViaCep\GetAddress as GetAddressFromViaCep;
use SystemCode\BrazilCustomerAttributes\Model\Address\Correios\GetAddress as GetAddressFromCorreios;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 *
 * Controller to consult address by zipcode
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
class Address extends Action implements HttpGetActionInterface
{
  
    protected $_resultPageFactory;

    protected $getAddressFromCorreios;

    protected $getAddressFromViaCep;

    /**
     * Address constructor.
     * @param Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param GetAddressFromCorreios $getAddressFromCorreios
     * @param GetAddressFromViaCep $getAddressFromViaCep
     */
    public function __construct(        
        Context $context,
        GetAddressFromCorreios $getAddressFromCorreios,
        GetAddressFromViaCep $getAddressFromViaCep
        JsonFactory $resultJsonFactory
        )
    {        
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->getAddressFromCorreios = $getAddressFromCorreios;
        $this->getAddressFromViaCep = $getAddressFromViaCep;
        parent::__construct($context);
    }

    public function execute()
    {         
        
        if($zipcode = $this->getRequest()->getParam('zipcode')){
                        
            $data = $this->getAddressFromViaCep->getAddress($zipcode);

            if ($data === false) {

                $data = $this->getAddressFromCorreios->getAddress($zipcode);
                
            }            

        }

        $return = $this->_resultJsonFactory->create();
        return $return->setData(str_replace("\\","",$data));
    }

}