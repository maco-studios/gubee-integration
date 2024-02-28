<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Model\Catalog\Product;

use Exception;
use Gubee\Integration\Helper\Config;
use Gubee\Integration\Service\Hydration\Catalog\Eav\Attribute\AttributeTrait;
use Gubee\Integration\Service\Model\Catalog\Product;
use Gubee\SDK\Model\Catalog\Product\Price as ProductPrice;
use Gubee\SDK\Resource\Catalog\Product\PriceResource;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\ObjectManagerInterface;

class Price extends ProductPrice
{
    use AttributeTrait;

    protected ProductInterface $product;
    protected PriceResource $priceResource;
    protected Config $config;
    protected ObjectManagerInterface $objectManager;

    public function __construct(
        ProductInterface $product,
        PriceResource $priceResource,
        ObjectManagerInterface $objectManager,
        Config $config
    ) {
        $this->product       = $product;
        $this->priceResource = $priceResource;
        $this->objectManager = $objectManager;
        $this->config        = $config;
        $this->setValue((float) $this->getDefaultPrice());
        $this->setType(self::DEFAULT);
    }

    /**
     * @return float
     */
    public function getDefaultPrice()
    {
        $priceAttribute = $this->config->getAttributePrice();
        if ($priceAttribute !== 'price') {
            return (float) $this->getRawAttributeValue(
                $this->product,
                $priceAttribute
            );
        }
        return (float) $this->product->getData($priceAttribute);
    }

    /**
     * @return mixed
     */
    public function save()
    {
        try {
            $product = $this->objectManager->create(
                Product::class,
                [
                    'product' => $this->product,
                ]
            );
            foreach ($product->getVariations() as $variation) {
                if (! $variation->getSkuId()) {
                    continue;
                }
                $this->getPriceApi()->getPriceByItemId(
                    $variation->getSkuId()
                );
                foreach ($variation->getPrices() as $price) {
                    $this->getPriceApi()->updatePrice(
                        $product->getId(),
                        $variation->getSkuId(),
                        $price
                    );
                }
            }
        } catch (Exception $e) {
            throw $e;
        }

        return $this;
    }

    public function getPriceApi(): PriceResource
    {
        return $this->priceResource;
    }
}
