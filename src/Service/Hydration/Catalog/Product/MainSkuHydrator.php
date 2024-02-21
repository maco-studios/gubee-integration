<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product;

class MainSkuHydrator extends AbstractHydrator
{
    /**
     * Extracts the main sku from the product
     *
     * @param ProductInterface $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getMainSku();
    }

    /**
     * Hydrates the main sku into the product
     *
     * @param ProductInterface $value
     * @param array|null $data
     * @return mixed
     */
    public function hydrate($value, ?array $data)
    {
        $value->setMainSku(
            $this->product->getSku()
        );
        return $value;
    }
}
