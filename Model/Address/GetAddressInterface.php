<?php

namespace SystemCode\BrazilCustomerAttributes\Model\Address;

interface GetAddressInterface{

    public function getAddress(string $postcode): array;

}