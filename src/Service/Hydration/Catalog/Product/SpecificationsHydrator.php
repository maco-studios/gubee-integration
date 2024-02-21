<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product;

use Gubee\SDK\Interfaces\Catalog\ProductInterface;

class SpecificationsHydrator extends AbstractHydrator
{
    /**
     * Extract the specifications from the product.
     *
     * @param ProductInterface $value
     * @return array
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getSpecifications();
    }

    /**
     * Hydrate the product specifications.
     *
     * @param ProductInterface $value
     * @param array|null $data
     * @return ProductInterface
     */
    public function hydrate($value, ?array $data)
    {
        return $value;
    }
}
