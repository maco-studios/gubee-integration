<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Model\Catalog;

use Exception;
use Gubee\SDK\Library\HttpClient\Exception\NotFoundException;
use Gubee\SDK\Resource\Catalog\CategoryResource;
use Laminas\Hydrator\Strategy\StrategyChain;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\ObjectManagerInterface;

class Category extends \Gubee\SDK\Model\Catalog\Category
{
    protected CategoryResource $categoryResource;
    protected StrategyChain $hydrator;

    public function __construct(
        CategoryInterface $category,
        CategoryResource $categoryResource,
        ObjectManagerInterface $objectManager,
        iterable $strategies = []
    ) {
        $this->categoryResource = $categoryResource;
        foreach ($strategies as &$strategy) {
            $strategy = $objectManager->create(
                $strategy
            );
            $strategy->setCategory($category);
        }
        $this->hydrator = $objectManager->create(
            StrategyChain::class,
            [
                'extractionStrategies' => $strategies,
            ]
        );
        $this->hydrator->hydrate($this);
    }

    public function load(string $id): Category
    {
        $category = $this->getCategoryApi()
            ->loadByExternalId($id);
        $this->getCategoryApi()
            ->getHydrator()
            ->hydrate(
                $category,
                $this
            );
        return $this;
    }

    public function save(): self
    {
        try {
            if ($this->getId()) {
                $response = $this->getCategoryApi()
                    ->update($this);
            } else {
                $response = $this->getCategoryApi()
                    ->create($this);
            }
        } catch (NotFoundException $e) {
            $response = $this->getCategoryApi()
                ->create($this);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->getCategoryApi()
                ->getHydrator()
                ->hydrate(
                    $response,
                    $this
                );
        }

        return $this;
    }

    public function getCategoryApi(): CategoryResource
    {
        return $this->categoryResource;
    }
}
