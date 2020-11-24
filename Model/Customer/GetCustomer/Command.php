<?php

declare(strict_types=1);

namespace SystemCode\BrazilCustomerAttributes\Model\Customer\GetCustomer;

use Magento\Customer\Api\Data\CustomerSearchResultsInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use SystemCode\BrazilCustomerAttributes\Helper\Data;
use SystemCode\BrazilCustomerAttributes\Model\Customer\GetCustomer\Builders\SearchCriteria;

/**
 * Class Command
 */
class Command
{
    /** @var Data */
    protected $helper;

    /** @var SearchCriteria */
    protected $searchCriteriaBuilder;

    /** @var CustomerRepositoryInterface */
    protected $customerRepository;

    /**
     * Command constructor.
     *
     * @param Data $helper
     * @param SearchCriteria $searchCriteriaBuilder
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Data $helper,
        SearchCriteria $searchCriteriaBuilder,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->helper = $helper;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param mixed  $customer
     * @param string $fieldName
     *
     * @return CustomerSearchResultsInterface
     * @throws LocalizedException
     */
    public function execute($customer, string $fieldName): CustomerSearchResultsInterface
    {
        $websiteScope = $this->helper->isAccountSharedByWebsite();
        $searchCriteria = $this->searchCriteriaBuilder->build($customer, $fieldName, $websiteScope);

        return $this->customerRepository->getList($searchCriteria);
    }
}
