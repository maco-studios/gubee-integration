<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Model\Catalog\Product\Attribute\Dimension;

use Gubee\SDK\Model\Catalog\Product\Attribute\Dimension\Weight as DimensionWeight;

class Weight extends DimensionWeight
{
    /**
     * @var string
     */
    public const POUND = "POUND";

    /**
     * Set the value of the weight.
     *
     * @param mixed $value
     */
    public function setValue($value): self
    {
        if ($this->getType() === self::POUND) {
            $value *= 0.453592;
        }
        return parent::setValue($value);
    }

    /**
     * Returns array representation of the object.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'type'  => $this->getType() !== self::POUND ?: self::KILOGRAM,
            'value' => $this->getValue(),
        ];
    }
}
