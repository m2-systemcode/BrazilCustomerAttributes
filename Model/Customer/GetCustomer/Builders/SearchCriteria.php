<?php

declare(strict_types=1);

namespace SystemCode\BrazilCustomerAttributes\Model\Customer\GetCustomer\Builders;

use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class SearchCriteria
 */
class SearchCriteria
{
    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /**
     * SearchCriteria constructor.
     *
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(SearchCriteriaBuilder $searchCriteriaBuilder) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @param mixed  $customer
     * @param string $fieldName
     * @param bool   $websiteScope
     *
     * @return \Magento\Framework\Api\SearchCriteria
     */
    public function build($customer, string $fieldName, bool $websiteScope): \Magento\Framework\Api\SearchCriteria
    {
        $this->searchCriteriaBuilder->addFilter($fieldName, $customer->getData($fieldName));

        if ($websiteScope){
            $this->searchCriteriaBuilder->addFilter('website_id', $customer->getWebsiteId());
        }

        $customerId = $customer->getId();
        if ($customerId) {
            $this->searchCriteriaBuilder->addFilter('entity_id', $customerId, 'neq');
        }

        return $this->searchCriteriaBuilder->create();
    }
}
