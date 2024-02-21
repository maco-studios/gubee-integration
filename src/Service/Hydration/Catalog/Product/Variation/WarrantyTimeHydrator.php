<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Variation;

use Gubee\Integration\Helper\Config;
use Gubee\SDK\Model\Catalog\Product\Attribute\Dimension\UnitTime;
use Magento\Framework\ObjectManagerInterface;

class WarrantyTimeHydrator extends AbstractHydrator
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
     * Extract the warranty time from the product.
     *
     * @param Variation $value The product variation.
     * @param object|null $object The object to extract the value from.
     * @return int
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getWarrantyTime();
    }

    /**
     * Hydrate the warranty time of the product.
     *
     * @param Variation $value The product variation.
     * @param array|null $data The data to hydrate the value with.
     * @return Variation
     */
    public function hydrate($value, ?array $data)
    {
        $warrantyTime = $this->objectManager->create(
            UnitTime::class
        );
        $warrantyTime->setValue(
            (int) $this->getRawAttributeValue(
                $this->product,
                'gubee_warranty_time'
            )
        )->setType(
            $this->getRawAttributeValue(
                $this->product,
                'gubee_warranty_time_unit'
            ) ?: UnitTime::DAYS
        );

        $value->setWarrantyTime($warrantyTime);
        return $value;
    }
}
