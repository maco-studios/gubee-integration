<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product;

class NameHydrator extends AbstractHydrator
{
    /**
     * Extracts the name from the product
     *
     * @param ProductInterface $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getName();
    }

    /**
     * Hydrates the name into the product
     *
     * @param ProductInterface $value
     * @param array|null $data
     * @return mixed
     */
    public function hydrate($value, ?array $data)
    {
        $value->setName(
            $this->product->getName()
        );
        return $value;
    }
}
