<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product;

class VariantAttributesHydrator extends AbstractHydrator
{
    /**
     * Extract the variant attributes from the product.
     *
     * @param mixed $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
    }

    /**
     * Hydrate the product variant attributes.
     *
     * @param mixed $value
     * @param array|null $data
     * @return mixed
     */
    public function hydrate($value, ?array $data)
    {
        return $value;
    }
}
