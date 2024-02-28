<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Model\Catalog;

use Exception;
use Gubee\SDK\Library\HttpClient\Exception\NotFoundException;
use Gubee\SDK\Resource\Catalog\Product\StockResource;
use Gubee\SDK\Resource\Catalog\ProductResource;
use Laminas\Hydrator\Strategy\StrategyChain;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\ObjectManagerInterface;

class Product extends \Gubee\SDK\Model\Catalog\Product
{
    protected ProductResource $productResource;
    protected StockResource $stockResource;
    protected StrategyChain $hydrator;

    public function __construct(
        ProductInterface $product,
        ObjectManagerInterface $objectManager,
        ProductResource $productResource,
        StockResource $stockResource,
        iterable $strategies = []
    ) {
        $this->productResource = $productResource;
        $this->stockResource   = $stockResource;
        foreach ($strategies as &$strategy) {
            $strategy = $objectManager->create(
                $strategy
            );
            $strategy->setProduct($product);
        }
        $this->hydrator = $objectManager->create(
            StrategyChain::class,
            [
                'extractionStrategies' => $strategies,
            ]
        );
        $this->hydrator->hydrate($this);
    }

    public function load(string $id, string $field = 'id'): Product
    {
        switch ($field) {
            case 'id':
                $product = $this->getProductApi()
                    ->loadById($id);
                break;
            case 'skuId':
                $product = $this->getProductApi()
                    ->loadBySkuId($id);
                break;
        }

        $this->getProductApi()
            ->getHydrator()
            ->hydrate(
                $product,
                $this
            );
        return $this;
    }

    public function save(): self
    {
        try {
            if ($this->getId()) {
                $this->getProductApi()
                    ->update($this);
                $response = $this->getProductApi()->loadById(
                    $this->getId()
                );
            } else {
                $response = $this->getProductApi()
                    ->create($this);
            }
        } catch (NotFoundException $e) {
            $response = $this->getProductApi()
                ->create($this);
        } catch (Exception $e) {
            throw $e;
        }

        return $this;
    }

    public function desativate()
    {
        $this->setStatus(self::INACTIVE);
        foreach ($this->getVariations() as $variation) {
            foreach ($variation->getStocks() as $stock) {
                $stock->setQty(0);
                $this->getStockResource()
                    ->updateStockBySkuId(
                        $this->getId(),
                        $variation->getSkuId(),
                        $stock
                    );
            }
        }
    }

    public function getProductApi(): ProductResource
    {
        return $this->productResource;
    }

    public function getStockResource(): StockResource
    {
        return $this->stockResource;
    }
}
