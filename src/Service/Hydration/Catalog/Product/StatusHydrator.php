<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product;

use Gubee\Integration\Helper\Config;
use Gubee\SDK\Interfaces\Catalog\ProductInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;

class StatusHydrator extends AbstractHydrator
{
    protected StockRegistryInterface $stockRegistry;

    public function __construct(
        Config $config,
        StockRegistryInterface $stockRegistry
    ) {
        $this->stockRegistry = $stockRegistry;
        parent::__construct($config);
    }

    /**
     * Extract the status from the product.
     *
     * @param ProductInterface $value
     * @return string
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getStatus();
    }

    /**
     * Hydrate the product status.
     *
     * @param ProductInterface $value
     * @param array|null $data
     * @return ProductInterface
     */
    public function hydrate($value, ?array $data)
    {
        $status = ProductInterface::ACTIVE;
        if (! $this->product->isSalable()) {
            $status = ProductInterface::INACTIVE;
        }

        $stockItem = $this->stockRegistry->getStockItem(
            $this->product->getId(),
            $this->product->getStore()->getWebsiteId()
        );
        if ($stockItem->getIsInStock() === false) {
            $status = ProductInterface::INACTIVE;
        }
        $value->setStatus($status);
        return $value;
    }
}
