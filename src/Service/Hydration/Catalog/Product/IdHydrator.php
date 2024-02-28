<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product;

use Gubee\SDK\Api\Catalog\ProductInterface;

class IdHydrator extends AbstractHydrator
{
    /**
     * Extract the id from the product.
     *
     * @param ProductInterface $value
     * @return string
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getId();
    }

    /**
     * Hydrate the product id.
     *
     * @param ProductInterface $value
     * @param array|null $data
     * @return ProductInterface
     */
    public function hydrate($value, ?array $data)
    {
        $value->setId(
            $this->product->getData(
                $this->config->getAttributeIdentifier()
            ) ?: $this->product->getId()
        );
        return $value;
    }
}
