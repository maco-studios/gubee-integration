<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Variation;

use Gubee\Integration\Helper\Config;
use Gubee\SDK\Model\Catalog\Product\Attribute\Dimension\UnitTime;
use Gubee\SDK\Model\Catalog\Product\Stock;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\ObjectManagerInterface;

class StocksHydrator extends AbstractHydrator
{
    protected ObjectManagerInterface $objectManager;
    protected StockRegistryInterface $stockRegistry;

    public function __construct(
        Config $config,
        StockRegistryInterface $stockRegistry,
        ObjectManagerInterface $objectManager
    ) {
        parent::__construct($config);
        $this->stockRegistry = $stockRegistry;
        $this->objectManager = $objectManager;
    }

    /**
     * Extract the stocks from the product.
     *
     * @param Variation $value The product variation.
     * @param object|null $object The object to extract the value from.
     * @return array
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getStocks();
    }

    /**
     * Hydrate the stocks of the product.
     *
     * @param Variation $value The product variation.
     * @param array|null $data The data to hydrate the value with.
     * @return Variation
     */
    public function hydrate($value, ?array $data)
    {
        $stocks    = [];
        $stock     = $this->objectManager->create(
            Stock::class
        );
        $stockItem = $this->stockRegistry->getStockItem(
            $this->product->getId()
        );
        $stock->setQty(
            (int) $stockItem->getQty() ?: 0
        )->setPriority(
            0
        );

        $crossDockingTime = $this->objectManager->create(
            UnitTime::class
        );
        $crossDockingTime->setValue(
            (int) $this->getRawAttributeValue(
                $this->product,
                'gubee_cross_docking_time'
            ) ?: -1
        )->setType(
            $this->getRawAttributeValue(
                $this->product,
                'gubee_cross_docking_time_unit'
            ) ?: UnitTime::DAYS
        );
        $stock->setCrossDockingTime($crossDockingTime);

        $stocks[] = $stock;
        $value->setStocks($stocks);

        return $value;
    }
}
