<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Category;

use Laminas\Hydrator\Strategy\StrategyInterface;
use Magento\Catalog\Api\Data\CategoryInterface;

abstract class AbstractHydrator implements StrategyInterface
{
    protected CategoryInterface $category;

    public function setCategory(CategoryInterface $category)
    {
        $this->category = $category;
    }

    public function getCategory(): CategoryInterface
    {
        return $this->category;
    }
}
