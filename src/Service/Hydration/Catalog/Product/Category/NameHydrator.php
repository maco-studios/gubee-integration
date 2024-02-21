<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Category;

class NameHydrator extends AbstractHydrator
{
    /**
     * Extract the name attribute from the object
     *
     * @param  CategoryInterface $value
     * @param  null|object $object (optional) The original object for context.
     * @return mixed       Returns the value that should be extracted.
     */
    public function extract($value, ?object $object = null)
    {
        return $object->getName();
    }

    /**
     * Hydrate to the object the name attribute
     *
     * @param  CategoryInterface $value
     * @param  null|array $data The original data for context.
     * @return mixed      Returns the value that should be hydrated.
     */
    public function hydrate($value, ?array $data)
    {
        $value->setName(
            $this->category->getName()
        );
        return $value;
    }
}
