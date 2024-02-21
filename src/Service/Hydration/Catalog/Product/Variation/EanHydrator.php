<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Variation;

use Gubee\SDK\Model\Catalog\Product\Variation;

class EanHydrator extends AbstractHydrator
{
    /**
     * Extracts the ean value from the object.
     *
     * @param Variation $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getEan();
    }

    /**
     * Hydrates the ean value into the object.
     *
     * @param Variation $value
     * @param array|null $data
     * @return Variation
     */
    public function hydrate($value, ?array $data)
    {
        $value->setEan(
            $this->getRawAttributeValue(
                $this->product,
                $this->config->getAttributeEan()
            ) ?: ''
        );

        return $value;
    }
}
