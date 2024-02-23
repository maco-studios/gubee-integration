<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Attribute;

use Gubee\SDK\Interfaces\Catalog\Product\AttributeInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

use function in_array;

class VariantHydrator extends AbstractHydrator
{
    /**
     * Hycrate a eav attribute type to a object
     *
     * @param AttributeInterface $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        return $object->getAttrType();
    }

    /**
     * Hycrate a object to a eav attribute type
     *
     * @param AttributeInterface $value
     * @param array|null $data
     * @return AttributeInterface
     */
    public function hydrate($value, ?array $data)
    {
        $value->setVariant($this->isVariant());

        return $value;
    }

    /**
     * Check if the attribute is a variant
     */
    protected function isVariant(): bool
    {
        if (
            $this->eavAttribute->getIsGlobal() === "1"
            && $this->eavAttribute->getIsUserDefined() === "1"
            && $this->eavAttribute->getFrontendInput() === "select"
        ) {
            if (
                ! $this->eavAttribute->getApplyTo()
                ||
                in_array(
                    Configurable::TYPE_CODE,
                    $this->eavAttribute->getApplyTo()
                )
            ) {
                return true;
            }
        }

        return false;
    }
}
