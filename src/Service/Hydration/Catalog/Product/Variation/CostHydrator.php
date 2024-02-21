<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Variation;

use Gubee\SDK\Model\Catalog\Product\Variation;

class CostHydrator extends AbstractHydrator
{
    /**
     * Extracts the cost value from the object.
     *
     * @param Variation $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getCost();
    }

    /**
     * Hydrates the cost value into the object.
     *
     * @param Variation $value
     * @param array|null $data
     * @return Variation
     */
    public function hydrate($value, ?array $data)
    {
        $value->setCost(
            $this->getRawAttributeValue(
                $this->product,
                'cost'
            ) ?: 0
        );

        return $value;
    }
}
