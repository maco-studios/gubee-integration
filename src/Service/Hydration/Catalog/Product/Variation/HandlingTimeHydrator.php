<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Variation;

use Gubee\Integration\Helper\Config;
use Gubee\SDK\Model\Catalog\Product\Attribute\Dimension\UnitTime;
use Gubee\SDK\Model\Catalog\Product\Variation;
use Magento\Framework\ObjectManagerInterface;

class HandlingTimeHydrator extends AbstractHydrator
{
    protected ObjectManagerInterface $objectManager;

    public function __construct(
        Config $config,
        ObjectManagerInterface $objectManager
    ) {
        parent::__construct($config);
        $this->objectManager = $objectManager;
    }

    /**
     * Extracts the handling time value from the object.
     *
     * @param Variation $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getHandlingTime();
    }

    /**
     * Hydrates the handling time value into the object.
     *
     * @param Variation $value
     * @param array|null $data
     * @return Variation
     */
    public function hydrate($value, ?array $data)
    {
        $unitTime = $this->objectManager->create(
            UnitTime::class
        );
        $unitTime->setValue(
            (float) $this->getRawAttributeValue(
                $this->product,
                'gubee_handling_time'
            ) ?: 0
        )->setType(
            $this->getRawAttributeValue(
                $this->product,
                'gubee_handling_time_unit'
            ) ?: UnitTime::DAYS
        );

        $value->setHandlingTime($unitTime);
        return $value;
    }
}
