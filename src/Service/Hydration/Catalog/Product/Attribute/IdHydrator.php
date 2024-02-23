<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Attribute;

use Gubee\SDK\Interfaces\Catalog\Product\AttributeInterface;

class IdHydrator extends AbstractHydrator
{
    /**
     * Extract the attribute label from the object
     *
     * @param AttributeInterface $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        return $object->getLabel();
    }

    /**
     * Hycrate to the object the attribute label
     *
     * @param AttributeInterface $value
     * @param array|null $data
     * @return AttributeInterface
     */
    public function hydrate($value, ?array $data)
    {
        $value->setId(
            $this->eavAttribute->getAttributeCode()
        );

        return $value;
    }
}
