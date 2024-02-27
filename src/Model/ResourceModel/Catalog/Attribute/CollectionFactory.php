<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\ResourceModel\Catalog\Attribute;

use Gubee\Integration\Helper\Config;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Eav\Api\Data\AttributeSearchResultsInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;

class CollectionFactory
{
    protected ProductAttributeRepositoryInterface $productAttributeRepository;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected FilterBuilder $filterBuilder;
    protected Config $config;

    public function __construct(
        ProductAttributeRepositoryInterface $productAttributeRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        Config $config
    ) {
        $this->config                     = $config;
        $this->filterBuilder              = $filterBuilder;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->searchCriteriaBuilder      = $searchCriteriaBuilder;
    }

    /**
     * Create a new collection of product attributes.
     */
    public function create(): AttributeSearchResultsInterface
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(
                $this->filterBuilder->setField('attribute_code')
                    ->setValue('gubee_%')
                    ->setConditionType('nlike')
                    ->create()
            )
            ->addFilter(
                $this->filterBuilder->setField('attribute_code')
                    ->setValue($this->config->getAttributeBrand())
                    ->setConditionType('neq')
                    ->create()
            )
            ->addFilter(
                $this->filterBuilder->setField('frontend_label')
                    ->setValue(true)
                    ->setConditionType('notnull')
                    ->create()
            )
            ->addFilter(
                $this->filterBuilder->setField('frontend_input')
                    ->setValue('gallery')
                    ->setConditionType('neq')
                    ->create()
            )
            ->addFilter(
                $this->filterBuilder->setField('backend_type')
                    ->setValue('static')
                    ->setConditionType('neq')
                    ->create()
            )
            ->addFilter(
                $this->filterBuilder->setField('is_user_defined')
                    ->setValue(true)
                    ->setConditionType('eq')
                    ->create()
            )
            ->create();

        return $this->productAttributeRepository->getList($searchCriteria);
    }
}
