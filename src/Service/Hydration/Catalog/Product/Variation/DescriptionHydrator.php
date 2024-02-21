<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Variation;

use Gubee\SDK\Model\Catalog\Product\Variation;

class DescriptionHydrator extends AbstractHydrator
{
    /**
     * Extracts the description value from the object.
     *
     * @param Variation $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getDescription();
    }

    /**
     * Hydrates the description value into the object.
     *
     * @param Variation $value
     * @param array|null $data
     * @return Variation
     */
    public function hydrate($value, ?array $data)
    {
        $value->setDescription(
            $this->getRawAttributeValue(
                $this->product,
                'description'
            ) ?: ''
        );

        return $value;
    }
}
