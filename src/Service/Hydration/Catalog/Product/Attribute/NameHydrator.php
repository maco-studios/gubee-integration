<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Attribute;

use Gubee\SDK\Interfaces\Catalog\Product\AttributeInterface;

class NameHydrator extends AbstractHydrator
{
    /**
     * Hycrate a eav attribute type to a object
     *
     * @param AttributeInterface $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        return $object->getName();
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
        $value->setName($this->eavAttribute->getAttributeCode());
        return $value;
    }
}
