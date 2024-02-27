<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Category;

use Exception;
use Gubee\Integration\Service\Model\Catalog\Category;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Registry;

class ParentHydrator extends AbstractHydrator
{
    protected ObjectManagerInterface $objectManager;
    protected CategoryRepositoryInterface $categoryRepository;
    protected Registry $register;

    public function __construct(
        ObjectManagerInterface $objectManager,
        CategoryRepositoryInterface $categoryRepository,
        Registry $register
    ) {
        $this->register           = $register;
        $this->objectManager      = $objectManager;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Extract the parent attribute from the object
     *
     * @param  CategoryInterface $value
     * @param  null|object $object (optional) The original object for context.
     * @return mixed       Returns the value that should be extracted.
     */
    public function extract($value, ?object $object = null)
    {
        return $object->getParent();
    }

    /**
     * Hydrate to the object the parent attribute
     *
     * @param  CategoryInterface $value
     * @param  null|array $data The original data for context.
     * @return mixed      Returns the value that should be hydrated.
     */
    public function hydrate($value, ?array $data)
    {
        if ($this->register->registry('category_parent_hydrator')) {
            return $value;
        }
        $this->register->register('category_parent_hydrator', true);
        try {
            $parentCategory = $this->categoryRepository->get(
                $this->category->getParentId()
            );
            $parent         = $this->objectManager->create(
                Category::class,
                [
                    'category' => $parentCategory,
                ]
            );

            $value->setParent($parent);
        } catch (Exception $e) {
        } finally {
            $this->register->unregister('category_parent_hydrator');
        }
        return $value;
    }
}
