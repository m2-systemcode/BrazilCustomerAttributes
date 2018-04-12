<?php

namespace Systemcode\BrazilCustomerAttributes\Block\Magento\Customer\Widget;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\OptionInterface;
use Magento\Customer\Helper\Address;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template\Context;
use SystemCode\BrazilCustomerAttributes\Helper\Data as Helper;

/**
 *
 * Block to render customer's gender attribute
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
class Persontype extends \Magento\Customer\Block\Widget\AbstractWidget
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    protected $helper;

    public $showCpf;

    public $showCnpj;

    public $selectedPersonType;

    /**
     * Create an instance of the Gender widget
     *
     * @param SystemCode\BrazilCustomerAttributes\Helper\Data $helper
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Helper\Address $addressHelper
     * @param CustomerMetadataInterface $customerMetadata
     * @param CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct(
        Helper $helper,
        Context $context,
        Address $addressHelper,
        CustomerMetadataInterface $customerMetadata,
        CustomerRepositoryInterface $customerRepository,
        Session $customerSession,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->_customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        parent::__construct($context, $addressHelper, $customerMetadata, $data);
        $this->_isScopePrivate = true;

        $this->showCpf = (($this->getPersonType()=="cpf" //o tipo da pessoa é cpf
                || $this->getConfigAdmin("general", "customer_edit") == "yesall") //ou ela pode trocar de grupo
            && ($this->getStatus("show", "cpf", "cpf") //os campos de cpf estão visíveis
                || $this->getStatus("show", "cpf", "rg")));


        $this->showCnpj = (($this->getPersonType() //o tipo da pessoa é cnpj
                || $this->getPersonType()==false //a pessoa ainda não tem um tipo (registro de usuário)
                || $this->getConfigAdmin("general", "customer_edit") == "yesall") //ou ela pode trocar de grupo
            && ($this->getStatus("show", "cnpj", "cnpj") //os campos de cpf estão visíveis
                || $this->getStatus("show", "cnpj", "ie")
                || $this->getStatus("show", "cnpj", "socialname")
                || $this->getStatus("show", "cnpj", "tradename")
                || $this->getConfigAdmin("cnpj", "copy_firstname")
                || $this->getConfigAdmin("cnpj", "copy_lastname")));
    }

    /**
     * Initialize block
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        //$this->setTemplate('SystemCode_BrazilCustomerAttributes::widget/persontype.phtml');
    }

    /**
     * Check if gender attribute enabled in system
     * @return bool
     */
    public function getConfigAdmin($group, $field)
    {
        return $this->helper->getConfig("brazilcustomerattributes/".$group."/".$field);
    }

    /**
     * Check if an attribute is visible or required
     * @return bool
     */
    public function getStatus($type, $group, $field)
    {
        $fieldConfig = $this->helper->getConfig("brazilcustomerattributes/".$group."/".$field."_show");

        if($type == "show" && $fieldConfig != "" ||
           $type == "required" && ($fieldConfig == "req" || $fieldConfig == "requni") ){
            return true;
        }
        return false;
    }


    /**
     * Get current customer from session
     *
     * @return CustomerInterface
     */
    public function getCustomer()
    {
        if($id = $this->_customerSession->getId()){
            return $this->customerRepository->getById($id);
        }
        return null;
    }

    /**
     * Returns options from gender attribute
     * @return OptionInterface[]
     */
    public function getCustomerValue($attribute)
    {
        if($this->getCustomer()!=null && $this->getCustomer()->getCustomAttribute($attribute)){
            return $this->getCustomer()->getCustomAttribute($attribute)->getValue();
        }
        return;
    }

    public function getPersonType()
    {
        //verifico se é exibido somente cpf ou somente cnpj
        if($this->showCpf == true && $this->showCnpj == false){
            return "cpf";
        }else if($this->showCpf == false && $this->showCnpj == true){
            return "cnpj";
        }

        //verificação pelo grupo do cliente
        if ($this->getConfigAdmin("general", "customer_group_cpf")
            != $this->getConfigAdmin("general", "customer_group_cnpj")
        ) {
            if ($this->_customerSession->getCustomer()->getGroupId() ==
                $this->getConfigAdmin("general", "customer_group_cpf")
            ) {
                return "cpf";
            } else if ($this->_customerSession->getCustomer()->getGroupId() ==
                $this->getConfigAdmin("general", "customer_group_cnpj")
            ) {
                return "cnpj";
            }
        }

        //verifico se o cliente tem algum dado de cnpj preenchido, caso não tenha assimilo como cpf
        if($this->getCustomerValue('cnpj')!="" ||
            $this->getCustomerValue('ie')!="" ||
            $this->getCustomerValue('socialname')!="" ||
            $this->getCustomerValue('tradename')!=""){
            return "cnpj";
        }
        return false;
    }

}
