<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Attribute;

use Gubee\SDK\Api\Catalog\Product\AttributeInterface;

use function trim;

class OptionsHydrator extends AbstractHydrator
{
    /**
     * Hycrate a eav attribute type to a object
     *
     * @param AttributeInterface $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        return $object->getOptions();
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
        $options = [];
        foreach ($this->eavAttribute->getOptions() ?: [] as $option) {
            $label = (string) $option->getLabel();
            $label = trim($label);
            if ($label === '') {
                continue;
            }
            $options[] = $label;
        }

        $value->setOptions($options);
        return $value;
    }
}
