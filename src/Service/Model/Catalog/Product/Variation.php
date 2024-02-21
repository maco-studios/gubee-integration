<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Model\Catalog\Product;

use Gubee\SDK\Model\Catalog\Product\Variation as ProductVariation;
use Laminas\Hydrator\Strategy\StrategyChain;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\ObjectManagerInterface;

class Variation extends ProductVariation
{
    protected StrategyChain $hydrator;

    public function __construct(
        ProductInterface $product,
        ObjectManagerInterface $objectManager,
        iterable $strategies = []
    ) {
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
}
