<?php

declare(strict_types=1);

namespace Gubee\Integration\Library\Setup\Migration\Facade\Catalog\Category;

use Gubee\Integration\Library\Setup\Migration\Eav\Attribute as EavAttribute;
use Gubee\Integration\Library\Setup\Migration\Eav\Context;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryAttributeInterface;

class Attribute extends EavAttribute
{
    public const ENTITY_TYPE = CategoryAttributeInterface::ENTITY_TYPE_CODE;

    protected CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        Context $context,
        CategoryRepositoryInterface $categoryRepository
    ) {
        parent::__construct($context);
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Update given attribute value for multiple categories at once
     *
     * @param array $entityIds
     * @param array $data
     * @return void
     */
    public function massUpdate($entityIds, $data)
    {
        foreach ($entityIds as $categoryId) {
            $category = $this->categoryRepository->get($categoryId);
            $category->addData($data);

            $this->categoryRepository->save($category);
        }
    }
}
