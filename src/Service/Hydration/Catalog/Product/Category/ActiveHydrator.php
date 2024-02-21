<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Category;

use Gubee\SDK\Interfaces\Catalog\CategoryInterface;

class ActiveHydrator extends AbstractHydrator
{
    /**
     * Extract the active attribute from the object
     *
     * @param  CategoryInterface $value
     * @param  null|object $object (optional) The original object for context.
     * @return mixed       Returns the value that should be extracted.
     */
    public function extract($value, ?object $object = null)
    {
        return $object->getActive();
    }

    /**
     * Hydrate to the object the active attribute
     *
     * @param  CategoryInterface $value
     * @param  null|array $data The original data for context.
     * @return mixed      Returns the value that should be hydrated.
     */
    public function hydrate($value, ?array $data)
    {
        $value->setActive(
            $this->category->getIsActive() ? true : false
        );
        return $value;
    }
}
