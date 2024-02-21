<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product;

class OriginHydrator extends AbstractHydrator
{
    /**
     * Extracts the origin from the product
     *
     * @param ProductInterface $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getOrigin();
    }

    /**
     * Hydrates the origin into the product
     *
     * @param ProductInterface $value
     * @param array|null $data
     * @return mixed
     */
    public function hydrate($value, ?array $data)
    {
        $origin = $this->getRawAttributeValue(
            $this->product,
            'gubee_origin'
        );
        if (! $origin) {
            return $value;
        }
        $value->setOrigin($origin);
        return $value;
    }
}
