<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Attribute;

use Laminas\Hydrator\Strategy\StrategyInterface;
use Magento\Catalog\Api\Data\EavAttributeInterface;

abstract class AbstractHydrator implements StrategyInterface
{
    protected EavAttributeInterface $eavAttribute;

    public function setEavAttribute(EavAttributeInterface $eavAttribute)
    {
        $this->eavAttribute = $eavAttribute;
    }

    public function getEavAttribute(): EavAttributeInterface
    {
        return $this->eavAttribute;
    }
}
