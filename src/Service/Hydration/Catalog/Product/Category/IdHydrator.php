<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Category;

class IdHydrator extends AbstractHydrator
{
    /**
     * Extract the id attribute from the object
     *
     * @param  CategoryInterface $value
     * @param  null|object $object (optional) The original object for context.
     * @return mixed       Returns the value that should be extracted.
     */
    public function extract($value, ?object $object = null)
    {
        return $object->getId();
    }

    /**
     * Hydrate to the object the id attribute
     *
     * @param  CategoryInterface $value
     * @param  null|array $data The original data for context.
     * @return mixed      Returns the value that should be hydrated.
     */
    public function hydrate($value, ?array $data)
    {
        $value->setId(
            $this->category->getId()
        );
        return $value;
    }
}
