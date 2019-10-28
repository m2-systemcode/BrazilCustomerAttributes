<?php

namespace SystemCode\BrazilCustomerAttributes\Model\Address\ViaCep;

use GuzzleHttp\Client;
use SystemCode\BrazilCustomerAttributes\Helper\Data;
use SystemCode\BrazilCustomerAttributes\Model\Address\GetAddressInterface;

class GetAddress implements GetAddressInterface{

    protected $baseUri = 'https://viacep.com.br/ws/';

    private $helper;

    public function __construct(
        Data $helper
    )
    {
        $this->helper = $helper;
    }


    public function getAddress(string $postcode): array
    {
        try {

            $client = new Client(
                [
                    'base_uri' => $this->baseUri
                ]
            );
    
            $response =  $client->get($postcode . '/json');
    
            $address = json_decode($response->getBody()->getContents(), true);
    
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