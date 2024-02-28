<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product;

use Gubee\SDK\Api\Catalog\ProductInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

use function count;

class TypeHydrator extends AbstractHydrator
{
    /**
     * Extract the type from the product.
     *
     * @param ProductInterface $value
     * @return string
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getType();
    }

    /**
     * Hydrate the product type.
     *
     * @param ProductInterface $value
     * @param array|null $data
     * @return ProductInterface
     */
    public function hydrate($value, ?array $data)
    {
        $type = ProductInterface::SIMPLE;

        if ($this->product->getTypeId() === Configurable::TYPE_CODE) {
            $type = ProductInterface::VARIANT;
        }
        $parents = $this->product->getTypeInstance()
            ->getParentIdsByChild(
                $this->product->getId()
            );

        if (count($parents) > 0) {
            $type = ProductInterface::VARIANT;
        }
        $value->setType($type);
        return $value;
    }
}
