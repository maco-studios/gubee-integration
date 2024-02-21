<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product;

use Gubee\Integration\Helper\Config;
use Gubee\Integration\Service\Model\Catalog\Category;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\ObjectManagerInterface;

class CategoriesHydrator extends AbstractHydrator
{
    protected ObjectManagerInterface $objectManager;
    protected CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        Config $config,
        CategoryRepositoryInterface $categoryRepository,
        ObjectManagerInterface $objectManager
    ) {
        parent::__construct($config);
        $this->objectManager      = $objectManager;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Extracts the category from the product
     *
     * @param ProductInterface $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getCategoryIds();
    }

    /**
     * Hydrates the category into the product
     *
     * @param ProductInterface $value
     * @param array|null $data
     * @return mixed
     */
    public function hydrate($value, ?array $data)
    {
        $categoryIds = $this->product->getCategoryIds();
        $categories  = [];
        foreach ($categoryIds as $categoryId) {
            $category     = $this->categoryRepository->get($categoryId);
            $categories[] = $this->objectManager->create(
                Category::class,
                ['category' => $category]
            );
        }
        $value->setCategories($categories);
        return $value;
    }
}
