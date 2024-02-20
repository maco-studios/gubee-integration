<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Model\Catalog;

use Exception;
use Gubee\SDK\Api\Catalog\ProductApi;
use Gubee\SDK\Library\HttpClient\Exception\NotFoundException;

class Product extends \Gubee\SDK\Model\Catalog\Product
{
    protected ProductApi $productApi;

    public function __construct(
        ProductApi $productApi
    ) {
        $this->productApi = $productApi;
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
