<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Attribute;

use Gubee\SDK\Api\Catalog\Product\AttributeInterface;

class AttrTypeHydrator extends AbstractHydrator
{
    /**
     * Extract the attribute type from the object
     *
     * @param AttributeInterface $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        return $object->getAttrType();
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
        switch ($this->eavAttribute->getFrontendInput()) {
            case 'textarea':
                $type = AttributeInterface::TEXTAREA;
                break;
            case 'multiselect':
                $type = AttributeInterface::MULTISELECT;
                break;
            case 'select':
                $type = AttributeInterface::SELECT;
                break;
            case 'text':
            case 'price':
            default:
                $type = AttributeInterface::TEXT;
                break;
        }
        $value->setAttrType($type);
        return $value;
    }
}
