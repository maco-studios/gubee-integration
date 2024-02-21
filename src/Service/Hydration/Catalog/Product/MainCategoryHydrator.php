<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product;

use Gubee\Integration\Helper\Config;
use Gubee\Integration\Model\System\Config\Source\Catalog\Category\Main;
use Gubee\Integration\Service\Model\Catalog\Category;
use Magento\Catalog\Api\CategoryListInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\ObjectManagerInterface;

use function array_shift;

class MainCategoryHydrator extends AbstractHydrator
{
    protected CategoryListInterface $categoryList;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected ObjectManagerInterface $objectManager;
    protected SortOrderBuilder $sortOrderBuilder;

    public function __construct(
        Config $config,
        CategoryListInterface $categoryList,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        ObjectManagerInterface $objectManager
    ) {
        parent::__construct($config);
        $this->categoryList          = $categoryList;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->objectManager         = $objectManager;
        $this->sortOrderBuilder      = $sortOrderBuilder;
    }

    /**
     * Extracts the main category from the product
     *
     * @param ProductInterface $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getMainCategory();
    }

    /**
     * Hydrates the main category into the product
     *
     * @param ProductInterface $value
     * @param array|null $data
     * @return mixed
     */
    public function hydrate($value, ?array $data)
    {
        $categoriesId   = $this->product->getCategoryIds();
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(
                'entity_id',
                $categoriesId,
                'in'
            )->addSortOrder(
                $this->sortOrderBuilder->setField(
                    'position',
                )->setDirection(
                    $this->config->getAttributeMainCategory() === Main::DEEPER
                        ? SortOrder::SORT_DESC
                        : SortOrder::SORT_ASC
                )->create()
            )->create();
        $categories     = $this->categoryList
            ->getList($searchCriteria)
            ->getItems();
        $mainCategory   = array_shift($categories);

        $value->setMainCategory(
            $this->objectManager->create(
                Category::class,
                ['category' => $mainCategory]
            )
        );

        return $value;
    }
}
