<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Eav\Attribute;

use Magento\Catalog\Api\Data\ProductInterface;

use function array_filter;
use function array_map;
use function explode;
use function is_array;

trait AttributeTrait
{
    /**
     * Get the raw attribute value of a product.
     *
     * @param ProductInterface $product The product object.
     * @param string $attributeCode The attribute code.
     * @return mixed|null The raw attribute value or null if not found.
     */
    public function getRawAttributeValue(ProductInterface $product, string $attributeCode)
    {
        $attribute = $this->getAttribute($product, $attributeCode);
        if (! $attribute) {
            return null;
        }

        if ($attribute->getFrontendInput() === 'multiselect') {
            return $this->getMultiSelectValue($product, $attribute, $attributeCode);
        }

        return $product->getResource()
            ->getAttributeRawValue(
                $product->getId(),
                $attributeCode,
                $product->getStoreId()
            );
    }

    /**
     * Get the label of an attribute value for a product.
     *
     * @param ProductInterface $product The product object.
     * @param string $attributeCode The attribute code.
     * @return mixed|null The attribute value label or null if not found.
     */
    public function getAttributeValueLabel(ProductInterface $product, string $attributeCode)
    {
        $attribute = $this->getAttribute($product, $attributeCode);
        if (! $attribute) {
            return null;
        }

        if ($attribute->getFrontendInput() === 'multiselect') {
            return $this->getMultiSelectValue($product, $attribute, $attributeCode);
        }

        return $attribute->getFrontend()->getValue($product);
    }

    /**
     * Get the attribute object for a given attribute code.
     *
     * @param ProductInterface $product The product object.
     * @param string $attributeCode The attribute code.
     * @return mixed|null The attribute object or null if not found.
     */
    private function getAttribute(ProductInterface $product, string $attributeCode)
    {
        return $product->getResource()->getAttribute($attributeCode);
    }

    /**
     * Get the multi-select attribute value for a product.
     *
     * @param ProductInterface $product The product object.
     * @param mixed $attribute The attribute object.
     * @param string $attributeCode The attribute code.
     * @return mixed|null The multi-select attribute value or null if not found.
     */
    private function getMultiSelectValue(ProductInterface $product, $attribute, string $attributeCode)
    {
        $value = $product->getData($attributeCode);
        if (! $value) {
            return null;
        }
        if (is_array($value)) {
            return $value;
        }
        $value = explode(',', $value ?: '');
        $value = array_filter($value);
        if (! $value) {
            return null;
        }
        $value = array_map(function ($value) use ($attribute) {
            return $attribute->getFrontend()->getOption($value);
        }, $value ?: []);

        return $value;
    }
}
