<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Model\Catalog\Product;

use Magento\Catalog\Api\Data\ProductInterface;
use Gubee\Integration\Api\Enum\MainCategoryEnum;
use Gubee\Integration\Helper\Catalog\Attribute;
use Magento\Catalog\Model\Product\Gallery\ReadHandler;
use Gubee\Integration\Model\Config;
use Gubee\Integration\Service\Hydrator\HydrationTrait;
use Gubee\SDK\Enum\Catalog\Product\Attribute\OriginEnum;
use Gubee\SDK\Enum\Catalog\Product\StatusEnum;
use Gubee\SDK\Enum\Catalog\Product\TypeEnum;
use Gubee\SDK\Model\Catalog\Product\Attribute\Brand;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Gubee\SDK\Resource\Catalog\ProductResource;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\DataObject;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\ObjectManagerInterface;

class Variation
{
    public const SEPARATOR = '-|GI|-';

    protected ProductInterface $product;
    protected ?ProductInterface $parent = null;
    protected \Gubee\SDK\Model\Catalog\Product\Variation $variation;
    protected Attribute $attribute;
    protected Config $config;
    protected ObjectManagerInterface $objectManager;
    protected StockItemInterface $stockItem;

    public function __construct(
        ProductInterface $product,
        Attribute $attribute,
        Config $config,
        ObjectManagerInterface $objectManager,
        StockRegistryInterface $stockRegistry,
        ReadHandler $galleryReadHandler,
        ProductInterface $parent = null
    )
    {
        if ($parent) {
            $this->parent = $parent;
        }
        $galleryReadHandler->execute($product);
        $this->product = $product;
        $this->attribute = $attribute;
        $this->config = $config;
        $this->objectManager = $objectManager;
        $this->stockItem = $stockRegistry->getStockItem($product->getId());
        $this->variation = $this->objectManager->create(
                \Gubee\SDK\Model\Catalog\Product\Variation::class,
            [
                $this->buildSkuId(),
                $this->buildImages(),
                $this->buildDimension(),
                $this->buildHandlingTime(),
                $this->buildName(),
                $this->buildSku(),
                $this->buildWarrantyTime(),
                $this->buildCost(),
                $this->buildDescription(),
                $this->buildEan(),
                $this->buildMain(),
                $this->buildPrices(),
                $this->buildStatus(),
                $this->buildStocks(),
                $this->buildVariantSpecification()
            ]
        );
    }

    protected function buildSkuId()
    {
        return $this->parent ? sprintf(
            "%s%s%s",
            $this->parent->getSku() ?: $this->product->getSku(),
            self::SEPARATOR,
            $this->product->getSku()
        ) : $this->product->getSku();
    }
    protected function buildImages()
    {
        $images = [];
        if (empty($this->product->getMediaGalleryImages())) {
            return [
                $this->createPlaceholder()
            ];
        }
        foreach ($this->product->getMediaGalleryImages() as $key => $image) {
            $images[] = $this->objectManager->create(
                    \Gubee\SDK\Model\Catalog\Product\Variation\Media\Image::class,
                [
                    'url' => $image->getUrl(),
                    'order' => $image->getPosition(),
                    'name' => $image->getLabel(),
                    'id' => $image->getId()
                ]
            );
        }
        return $images;
    }

    private function createPlaceholder()
    {
        return $this->objectManager->create(
                \Gubee\SDK\Model\Catalog\Product\Variation\Media\Image::class,
            [
                'url' => '#',
                'order' => 0,
                'name' => 'Placeholder',
                'id' => 0
            ]
        );
    }

    protected function buildDimension()
    {
        $height = $this->objectManager->create(
            Measure::class,
            [
                'value' => $this->attribute->getRawAttributeValue(
                    $this->config->getHeightAttribute(),
                    $this->product
                ),
                'type' => $this->attribute->getRawAttributeValue(
                    $this->config->getMeasureUnitAttribute(),
                    $this->product
                )
            ]
        );
        $width = $this->objectManager->create(
            Measure::class,
            [
                'value' => $this->attribute->getRawAttributeValue(
                    $this->config->getWidthAttribute(),
                    $this->product
                ),
                'type' => $this->attribute->getRawAttributeValue(
                    $this->config->getMeasureUnitAttribute(),
                    $this->product
                )
            ]
        );
        $depth = $this->objectManager->create(
            Measure::class,
            [
                'value' => $this->attribute->getRawAttributeValue(
                    $this->config->getDepthAttribute(),
                    $this->product
                ),
                'type' => $this->attribute->getRawAttributeValue(
                    $this->config->getMeasureUnitAttribute(),
                    $this->product
                )
            ]
        );
        $weight = $this->objectManager->create(
            Weight::class,
            [
                'value' => $this->attribute->getRawAttributeValue(
                    'weight',
                    $this->product
                ),
                'type' => $this->config->getWeightUnit(),
            ]
        );

        return $this->objectManager->create(
                \Gubee\SDK\Model\Catalog\Product\Variation\Dimension::class,
            [
                'height' => $height,
                'width' => $width,
                'length' => $length,
                'weight' => $weight
            ]
        );
    }
    protected function buildHandlingTime()
    {

    }
    protected function buildName()
    {

    }
    protected function buildSku()
    {

    }
    protected function buildWarrantyTime()
    {

    }
    protected function buildCost()
    {

    }
    protected function buildDescription()
    {

    }
    protected function buildEan()
    {

    }
    protected function buildMain()
    {

    }
    protected function buildPrices()
    {

    }
    protected function buildStatus()
    {

    }
    protected function buildStocks()
    {

    }
    protected function buildVariantSpecification()
    {

    }

}