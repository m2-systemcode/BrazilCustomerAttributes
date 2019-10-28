<?php

namespace SystemCode\BrazilCustomerAttributes\Model\Address\Correios;

use SystemCode\BrazilCustomerAttributes\Helper\Data;
use SystemCode\BrazilCustomerAttributes\Model\Address\GetAddressInterface;

class GetAddress implements GetAddressInterface{

    private $helper;

    public function __construct(
        Data $helper
    ){
        $this->helper = $helper;
    }          


    public function getAddress(string $postcode): array
    {
       
        try {
            $client = new \SoapClient('https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl',
                ['exceptions' => true]);
            $result = $client->consultaCEP(['cep' => $postcode]);

            if(isset($result)){
                $complement = trim(implode(' ', array($result->return->complemento??'', $result->return->complemento2??'')));
                $data = [
                    'error' => false,
                    'zipcode' => $postcode,
                    'street' => $result->return->end,
                    'neighborhood' => $result->return->bairro,
                    'complement' => $complement,
                    'city' => $result->return->cidade,
                    'uf' => $this->helper->getRegionId($result->return->uf)
                ];
            }

            return $data;
            

        } catch (\SoapFault $e) {
            
        }

        return ["error" => true];

    }

}