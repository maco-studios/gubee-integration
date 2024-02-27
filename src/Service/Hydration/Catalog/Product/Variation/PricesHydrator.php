<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Variation;

use Gubee\Integration\Helper\Config;
use Gubee\SDK\Model\Catalog\Product\Price;
use Magento\Framework\ObjectManagerInterface;

class PricesHydrator extends AbstractHydrator
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
     * Extract the prices from the product.
     *
     * @param Variation $value The product variation.
     * @param object|null $object The object to extract the value from.
     * @return array
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getPrices();
    }

    /**
     * Hydrate the prices of the product.
     *
     * @param Variation $value The product variation.
     * @param array|null $data The data to hydrate the value with.
     * @return Variation
     */
    public function hydrate($value, ?array $data)
    {
        $value->setPrices($this->getPrices());
        return $value;
    }

    protected function getPrices(): array
    {
        $prices = [];
        $price  = $this->objectManager->create(
            Price::class
        );
        $price->setValue(
            (float) $this->getRawAttributeValue(
                $this->product,
                $this->config->getAttributePrice()
            ) ?: 0
        )->setType(
            Price::DEFAULT
        );

        return [$price];
    }
}
