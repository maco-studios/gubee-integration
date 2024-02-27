<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Variation;

use Gubee\Integration\Helper\Config;
use Gubee\Integration\Service\Model\Catalog\Product\Attribute\Dimension\Weight;
use Gubee\SDK\Interfaces\Catalog\Product\Attribute\Dimension\MeasureInterface;
use Gubee\SDK\Interfaces\Catalog\Product\Attribute\Dimension\WeightInterface;
use Gubee\SDK\Model\Catalog\Product\Attribute\Dimension;
use Gubee\SDK\Model\Catalog\Product\Attribute\Dimension\Measure;
use Gubee\SDK\Model\Catalog\Product\Variation;
use Magento\Framework\ObjectManagerInterface;

class DimensionHydrator extends AbstractHydrator
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
     * Extracts the dimension value from the object.
     *
     * @param Variation $value
     * @return Dimension
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getDimension();
    }

    /**
     * Hydrates the dimension value into the object.
     *
     * @param Variation $value
     * @param array|null $data
     * @return Variation
     */
    public function hydrate($value, ?array $data)
    {
        $depth = $this->objectManager->create(
            Measure::class
        );
        $depth->setValue(
            (float) $this->getRawAttributeValue(
                $this->product,
                $this->config->getAttributeDepth()
            ) ?: 0
        )->setType(
            $this->getRawAttributeValue(
                $this->product,
                $this->config->getAttributeMeasureUnit()
            ) ?: MeasureInterface::CENTIMETER
        );

        $height = $this->objectManager->create(
            Measure::class
        );
        $height->setValue(
            (float) $this->getRawAttributeValue(
                $this->product,
                $this->config->getAttributeHeight()
            ) ?: 0
        )->setType(
            $this->getRawAttributeValue(
                $this->product,
                $this->config->getAttributeMeasureUnit()
            ) ?: MeasureInterface::CENTIMETER
        );

        $width = $this->objectManager->create(
            Measure::class
        );
        $width->setValue(
            (float) $this->getRawAttributeValue(
                $this->product,
                $this->config->getAttributeWidth()
            ) ?: 0
        )->setType(
            $this->getRawAttributeValue(
                $this->product,
                $this->config->getAttributeMeasureUnit()
            ) ?: MeasureInterface::CENTIMETER
        );
        $weight = $this->objectManager->create(
            Weight::class
        );
        $weight->setType(
            $this->config->getWeightUnit() ?: WeightInterface::KILOGRAM
        )->setValue(
            (float) $this->getRawAttributeValue(
                $this->product,
                'weight'
            ) ?: 0
        );

        $dimension = $this->objectManager->create(
            Dimension::class
        );
        $dimension->setDepth($depth)
            ->setHeight($height)
            ->setWidth($width)
            ->setWeight($weight);
        $value->setDimension($dimension);

        return $value;
    }
}
