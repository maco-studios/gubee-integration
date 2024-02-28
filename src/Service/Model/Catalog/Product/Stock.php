<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Model\Catalog\Product;

use Exception;
use Gubee\Integration\Helper\Config;
use Gubee\Integration\Service\Hydration\Catalog\Eav\Attribute\AttributeTrait;
use Gubee\Integration\Service\Model\Catalog\Product;
use Gubee\SDK\Model\Catalog\Product\Attribute\Dimension\UnitTime;
use Gubee\SDK\Model\Catalog\Product\Stock as ProductStock;
use Gubee\SDK\Resource\Catalog\Product\StockResource;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\ObjectManagerInterface;

class Stock extends ProductStock
{
    use AttributeTrait;

    protected ProductInterface $product;
    protected StockResource $stockResource;
    protected Config $config;
    protected ObjectManagerInterface $objectManager;
    protected StockItemInterface $stockItem;

    public function __construct(
        ProductInterface $product,
        StockResource $stockResource,
        Config $config,
        ObjectManagerInterface $objectManager,
        StockRegistryInterface $stockRegistry
    ) {
        $this->objectManager = $objectManager;
        $this->product       = $product;
        $this->stockResource = $stockResource;
        $this->config        = $config;
        $this->stockItem     = $stockRegistry->getStockItem($product->getId());
        $this->setQty(
            (int) $this->stockItem->getQty()
        );
        $crossDockingTime = $objectManager->create(
            UnitTime::class
        );
        $crossDockingTime->setType(
            $this->getRawAttributeValue(
                $this->product,
                'gubee_cross_docking_time_unit'
            ) ?: UnitTime::DAYS
        )->setValue(
            (int) $this->getRawAttributeValue(
                $this->product,
                'gubee_cross_docking_time'
            ) ?: -1
        );

        $this->setCrossDockingTime($crossDockingTime);
        $this->setPriority(0);
    }

    public function save(): self
    {
        try {
            $product = $this->objectManager->create(
                Product::class,
                ['product' => $this->product]
            );
            foreach ($product->getVariations() as $variation) {
                foreach ($variation->getStocks() as $stock) {
                    $this->getStockApi()->updateStockBySkuId(
                        $product->getId(),
                        $variation->getSkuId(),
                        $stock
                    );
                }
            }
        } catch (Exception $e) {
            throw $e;
        }

        return $this;
    }

    public function desativate(): self
    {
        try {
            $product = $this->objectManager->create(
                Product::class,
                ['product' => $this->product]
            );
            foreach ($product->getVariations() as $variation) {
                foreach ($variation->getStocks() as $stock) {
                    $stock->setQty(0);
                    $this->getStockApi()->updateStockBySkuId(
                        $product->getId(),
                        $variation->getSkuId(),
                        $stock
                    );
                }
            }
        } catch (Exception $e) {
            throw $e;
        }

        return $this;
    }

    public function getStockApi(): StockResource
    {
        return $this->stockResource;
    }
}
