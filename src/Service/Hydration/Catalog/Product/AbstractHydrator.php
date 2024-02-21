<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product;

use Gubee\Integration\Helper\Config;
use Gubee\Integration\Service\Hydration\Catalog\Eav\Attribute\AttributeTrait;
use Laminas\Hydrator\Strategy\StrategyInterface;
use Magento\Catalog\Api\Data\ProductInterface;

abstract class AbstractHydrator implements StrategyInterface
{
    use AttributeTrait;

    protected ProductInterface $product;
    protected Config $config;

    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    public function setProduct(ProductInterface $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getProduct(): ProductInterface
    {
        return $this->product;
    }
}
