<?php

namespace SystemCode\BrazilCustomerAttributes\Helper;

use Magento\Store\Model\ScopeInterface;

/**
 *
 * Helper for validations and commons functions
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
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const ACCOUNT_SHARE_SCOPE_FLAG_PATH  = 'customer/account_share/scope';
    const COPY_CNPJ_SOCIAL_NAME = 'brazilcustomerattributes/cnpj/copy_firstname';
    const COPY_CNPJ_TRADE_NAME = 'brazilcustomerattributes/cnpj/copy_lastname';

    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param string $config_path
     * @param null|mixed $storeId
     *
     * @return bool
     */
    protected function isSetFlag(string $config_path, $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            $config_path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    function validateCPF($cpf) {
        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        $invalidos = array('00000000000', '11111111111', '22222222222', '33333333333', '44444444444', '55555555555', '66666666666', '77777777777', '88888888888', '99999999999');

        if (in_array($cpf, $invalidos)){
            return false;
        }

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }


    function validateCNPJ($cnpj) {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

        if (strlen($cnpj) <> 14)
            return false;

        $sum = 0;

        $sum += ($cnpj[0] * 5);
        $sum += ($cnpj[1] * 4);
        $sum += ($cnpj[2] * 3);
        $sum += ($cnpj[3] * 2);
        $sum += ($cnpj[4] * 9);
        $sum += ($cnpj[5] * 8);
        $sum += ($cnpj[6] * 7);
        $sum += ($cnpj[7] * 6);
        $sum += ($cnpj[8] * 5);
        $sum += ($cnpj[9] * 4);
        $sum += ($cnpj[10] * 3);
        $sum += ($cnpj[11] * 2);

        $d1 = $sum % 11;
        $d1 = $d1 < 2 ? 0 : 11 - $d1;

        $sum = 0;
        $sum += ($cnpj[0] * 6);
        $sum += ($cnpj[1] * 5);
        $sum += ($cnpj[2] * 4);
        $sum += ($cnpj[3] * 3);
        $sum += ($cnpj[4] * 2);
        $sum += ($cnpj[5] * 9);
        $sum += ($cnpj[6] * 8);
        $sum += ($cnpj[7] * 7);
        $sum += ($cnpj[8] * 6);
        $sum += ($cnpj[9] * 5);
        $sum += ($cnpj[10] * 4);
        $sum += ($cnpj[11] * 3);
        $sum += ($cnpj[12] * 2);


        $d2 = $sum % 11;
        $d2 = $d2 < 2 ? 0 : 11 - $d2;

        if ($cnpj[12] == $d1 && $cnpj[13] == $d2) {
            return true;
        }
        else {
            return false;
        }

    }

    public function getRegionId($state){

        $states = array(
            "AC"=>"Acre",
            "AL"=>"Alagoas",
            "AM"=>"Amazonas",
            "AP"=>"Amapá",
            "BA"=>"Bahia",
            "CE"=>"Ceará",
            "DF"=>"Distrito Federal",
            "ES"=>"Espírito Santo",
            "GO"=>"Goiás",
            "MA"=>"Maranhão",
            "MT"=>"Mato Grosso",
            "MS"=>"Mato Grosso do Sul",
            "MG"=>"Minas Gerais",
            "PA"=>"Pará",
            "PB"=>"Paraíba",
            "PR"=>"Paraná",
            "PE"=>"Pernambuco",
            "PI"=>"Piauí",
            "RJ"=>"Rio de Janeiro",
            "RN"=>"Rio Grande do Norte",
            "RO"=>"Rondônia",
            "RS"=>"Rio Grande do Sul",
            "RR"=>"Roraima",
            "SC"=>"Santa Catarina",
            "SE"=>"Sergipe",
            "SP"=>"São Paulo",
            "TO"=>"Tocantins");

        if($states[$state]){
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            $region = $objectManager->create('Magento\Directory\Model\Region')
                ->loadByName($states[$state], "BR");

            return $region->getId();
        }

    }

    /**
     * @return bool
     */
    public function isAccountSharedByWebsite(): bool
    {
        return $this->isSetFlag(self::ACCOUNT_SHARE_SCOPE_FLAG_PATH);
    }

    /**
     * @return bool
     */
    public function copySocialName(): bool
    {
        return $this->isSetFlag(self::COPY_CNPJ_SOCIAL_NAME);
    }

    /**
     * @return bool
     */
    public function copyTradeName(): bool
    {
        return $this->isSetFlag(self::COPY_CNPJ_TRADE_NAME);
    }
}
