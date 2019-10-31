<?php

namespace SystemCode\BrazilCustomerAttributes\Model\Address\ViaCep;

use SystemCode\BrazilCustomerAttributes\Helper\Data;
use SystemCode\BrazilCustomerAttributes\Model\Address\GetAddressInterface;
use Magento\Framework\HTTP\Client\CurlFactory;

class GetAddress implements GetAddressInterface{

    protected $baseUri = 'https://viacep.com.br/ws/';

    private $helper;

    private $curlFactory;

    public function __construct(
        Data $helper,
        CurlFactory $curlFactory
        
    )
    {
        $this->helper = $helper;
        $this->curlFactory = $curlFactory;
    }


    public function getAddress(string $postcode): array
    {
        try {
           
            $curlClient = $this->curlFactory->create();

            $curlClient->get($this->baseUri . $postcode . '/json');
    
            $address = json_decode($curlClient->getBody(), true);
    
            $data = [
                'error' => false,
                'zipcode' => $postcode,
                'street' => $address['logradouro'],
                'neighborhood' => $address['bairro'],
                'complement' => $address['complemento'],
                'city' => $address['localidade'],
                'uf' => $this->helper->getRegionId($address['uf'])
            ];
    
            return $data;
        } catch (\Throwable $th) {
            return false;
        }

    }

}