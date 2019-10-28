<?php

namespace SystemCode\BrazilCustomerAttributes\Controller\Consult;

use Magento\Framework\App\Action\Context;
use SystemCode\BrazilCustomerAttributes\Model\Address\ViaCep\GetAddress as GetAddressFromViaCep;
use SystemCode\BrazilCustomerAttributes\Model\Address\Correios\GetAddress as GetAddressFromCorreios;
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
class Address extends \Magento\Framework\App\Action\Action
{
    protected $helper;

    protected $_resultPageFactory;

    protected $getAddressFromCorreios;

    protected $getAddressFromViaCep;

    /**
     * Address constructor.
     * @param Helper $helper
     * @param Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(        
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        GetAddressFromCorreios $getAddressFromCorreios,
        GetAddressFromViaCep $getAddressFromViaCep
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

            $data = $this->getAddressFromCorreios->getAddress($zipcode);

            if ($data === false) {

                $data = $this->getAddressFromCorreios->getAddress($zipcode);
                
            }            

        }

        $return = $this->_resultJsonFactory->create();
        return $return->setData(str_replace("\\","",$data));
    }

}