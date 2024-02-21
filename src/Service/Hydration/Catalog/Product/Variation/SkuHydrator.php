<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Variation;

class SkuHydrator extends AbstractHydrator
{
    /**
     * Extract the SKU from the product.
     *
     * @param Variation $value The product variation.
     * @param object|null $object The object to extract the value from.
     * @return string
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getSku();
    }

    /**
     * Hydrate the SKU of the product.
     *
     * @param Variation $value The product variation.
     * @param array|null $data The data to hydrate the value with.
     * @return Variation
     */
    public function hydrate($value, ?array $data)
    {
        $value->setSku($this->product->getSku());
        return $value;
    }
}
