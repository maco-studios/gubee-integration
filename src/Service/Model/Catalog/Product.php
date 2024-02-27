<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Model\Catalog;

use Exception;
use Gubee\SDK\Api\Catalog\ProductApi;
use Gubee\SDK\Library\HttpClient\Exception\NotFoundException;
use Laminas\Hydrator\Strategy\StrategyChain;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\ObjectManagerInterface;

class Product extends \Gubee\SDK\Model\Catalog\Product
{
    protected ProductApi $productApi;
    protected StrategyChain $hydrator;

    public function __construct(
        ProductInterface $product,
        ObjectManagerInterface $objectManager,
        ProductApi $productApi,
        iterable $strategies = []
    ) {
        $this->productApi = $productApi;
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
        // echo json_encode(
        //     $this,
        //     JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
        // );
        // exit;

        try {
            if ($this->getId()) {
                $response = $this->getProductApi()
                    ->update($this);
            } else {
                $response = $this->getProductApi()
                    ->create($this);
            }
        } catch (NotFoundException $e) {
            $response = $this->getProductApi()
                ->create($this);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->getProductApi()
                ->getHydrator()
                ->hydrate(
                    $response,
                    $this
                );
        }

        return $this;
    }

    public function getProductApi(): ProductApi
    {
        return $this->productApi;
    }
}
