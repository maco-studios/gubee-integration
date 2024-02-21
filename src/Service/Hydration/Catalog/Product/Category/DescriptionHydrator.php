<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Category;

class DescriptionHydrator extends AbstractHydrator
{
    /**
     * Extract the description attribute from the object
     *
     * @param  CategoryInterface $value
     * @param  null|object $object (optional) The original object for context.
     * @return mixed       Returns the value that should be extracted.
     */
    public function extract($value, ?object $object = null)
    {
        return $object->getDescription();
    }

    /**
     * Hydrate to the object the description attribute
     *
     * @param  CategoryInterface $value
     * @param  null|array $data The original data for context.
     * @return mixed      Returns the value that should be hydrated.
     */
    public function hydrate($value, ?array $data)
    {
        $description = $this->category->getDescription();
        if (! $description) {
            return $value;
        }

        $value->setDescription(
            $description
        );
        return $value;
    }
}
