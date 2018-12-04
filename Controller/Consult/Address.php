<?php

namespace SystemCode\BrazilCustomerAttributes\Controller\Consult;

use Magento\Framework\App\Action\Context;
use SystemCode\BrazilCustomerAttributes\Helper\Data as Helper;

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

    /**
     * Address constructor.
     * @param Helper $helper
     * @param Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        Helper $helper,
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory)
    {
        $this->helper = $helper;
        $this->_resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = ["error" => true];

        if($zipcode = $this->getRequest()->getParam('zipcode')){
            try {
                $client = new \SoapClient('https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl',
                    ['exceptions' => true]);
                $result = $client->consultaCEP(['cep' => $zipcode]);
            } catch (\SoapFault $e) {

            }

            if(isset($result)){
                $complement = trim(implode(' ', array($result->return->complemento??'', $result->return->complemento2??'')));
                $data = [
                    'error' => false,
                    'zipcode' => $zipcode,
                    'street' => $result->return->end,
                    'neighborhood' => $result->return->bairro,
                    'complement' => $complement,
                    'city' => $result->return->cidade,
                    'uf' => $this->helper->getRegionId($result->return->uf)
                ];
            }
        }

        $return = $this->_resultJsonFactory->create();
        return $return->setData(str_replace("\\","",$data));
    }
}