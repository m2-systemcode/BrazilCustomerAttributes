<?php

namespace SystemCode\BrazilCustomerAttributes\Observer;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Event\Observer;
use \Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use SystemCode\BrazilCustomerAttributes\Helper\Data as Helper;
use \Magento\Customer\Model\Session;
use SystemCode\BrazilCustomerAttributes\Model\Customer\GetCustomer\Command as GetCustomerCommand;

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
class CustomerValidations implements ObserverInterface
{
    const EXEMPT_IE = 'EXEMPT';

    /** @var RequestInterface */
    private $request;

    /** @var Helper */
    private $helper;

    /** @var Session */
    private $session;

    /** @var GetCustomerCommand */
    private $getCustomerCommand;

    /** @var CustomerInterface */
    private $customer = null;

    public function __construct(
        RequestInterface $request,
        Helper $helper,
        Session $session,
        GetCustomerCommand $getCustomerCommand
    ) {
        $this->request = $request;
        $this->helper = $helper;
        $this->session = $session;
        $this->getCustomerCommand = $getCustomerCommand;
    }

    /**
     * @param Observer $observer
     *
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        $params = $this->request->getParams();
        $personType = $params['person_type'] ?? null;
        $this->customer = $observer->getCustomer();

        if ($personType === 'cpf') {
            $cpf = $params['cpf'] ?? '';
            $rg  = $params['rg']  ?? '';

            if ($cpf !== '' && ($this->helper->validateCPF($cpf) === false)) {
                throw new CouldNotSaveException(
                    __('%1 is invalid.', 'CPF')
                );
            }

            if ($this->_validateInput($cpf, 'cpf', 'cpf/cpf_show') === false){
                throw new CouldNotSaveException(
                    __('%1 already in use.', 'CPF')
                );
            }

            if ($this->_validateInput($rg, 'rg', 'cpf/rg_show') === false) {
                throw new CouldNotSaveException(
                    __('%1 already in use.', 'RG')
                );
            }
        } elseif ($personType === 'cnpj') {
            if ($this->helper->copySocialName()) {
                $firstName = $params['firstname'];
                $params['socialname'] = $firstName;
                $this->customer->setData('socialname', $firstName);
            }

            if ($this->helper->copyTradeName()) {
                $lastname = $params['lastname'];
                $params['tradename'] = $lastname;
                $this->customer->setData('tradename', $lastname);
            }

            $cnpj = $params['cnpj'] ?? '';
            $ie   = strtoupper($params['ie'] ?? __(self::EXEMPT_IE));
            $socialName = $params['socialname'] ?? '';
            $tradeName  = $params['tradename']  ?? '';

            if ($cnpj !== '' && ($this->helper->validateCNPJ($cnpj) === false)) {
                throw new CouldNotSaveException(
                    __('%1 is invalid.', 'CNPJ')
                );
            }

            if ($this->_validateInput($cnpj, 'cnpj', 'cnpj/cnpj_show') === false) {
                throw new CouldNotSaveException(
                    __('%1 already in use.', 'CNPJ')
                );
            }

            if (
                $ie !== strtoupper(__(self::EXEMPT_IE))
                && $this->_validateInput($ie, 'ie', 'cnpj/ie_show') === false
            ) {
                throw new CouldNotSaveException(
                    __('%1 already in use.', 'IE')
                );
            }

            if ($this->_validateInput($socialName, 'socialname', 'cnpj/socialname_show') === false) {
                throw new CouldNotSaveException(
                    __('%1 already in use.', 'Social Name')
                );
            }

            if ($this->_validateInput($tradeName, 'tradename', 'cnpj/tradename_show') === false) {
                throw new CouldNotSaveException(
                    __('%1 already in use.', 'Trade Name')
                );
            }
        }
    }

    /**
     * @param $value
     * @param $fieldName
     * @param $path
     *
     * @return bool
     * @throws LocalizedException
     */
    protected function _validateInput($value, $fieldName, $path): bool
    {
        $show = $this->helper->getConfig('brazilcustomerattributes/'.$path);

        if ($show === 'req' || $show === 'requni') { // checks if is required
            if ($value === '') {
                return false;
            }
        }

        if ($show === 'requni' || $show === 'optuni') { // checks if is unique
            return (
                (
                    $this->session->getCustomer()->getId() !== ''
                    && $this->session->getCustomer()->getData($fieldName) === $value
                ) || $this->isUnique($fieldName)
            );
        }

        return true;
    }

    /**
     * @param string $fieldName
     *
     * @return bool
     * @throws LocalizedException
     */
    protected function isUnique(string $fieldName): bool
    {
        $customerList = $this->getCustomerCommand->execute($this->customer, $fieldName);

        return $customerList->getTotalCount() <= 0;
    }
}
