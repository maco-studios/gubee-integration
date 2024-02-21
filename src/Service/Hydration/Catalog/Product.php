<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog;

use Gubee\SDK\Model\Catalog\Product as CatalogProduct;
use Magento\Catalog\Api\Data\ProductInterface;

class Product
{
    public function hydrate(CatalogProduct $product, ProductInterface $magentoProduct): void
    {
        $product->setAccounts(
            $this->getAccounts()
        )->setBrand(
            $this->getBrand()
        )->setCategories(
            $this->getCategories()
        )->setHubeeId(
            $this->getHubeeId()
        )->setId(
            $this->getId()
        )->setMainCategory(
            $this->getMainCategory()
        )->setMainSku(
            $this->getMainSku()
        )->setName(
            $this->getName()
        )->setNbm(
            $this->getNbm()
        )->setOrigin(
            $this->getOrigin()
        )->setSpecifications(
            $this->getSpecifications()
        )->setStatus(
            $this->getStatus()
        )->setType(
            $this->getType()
        )->setVariantAttributes(
            $this->getVariantAttributes()
        )->setVariations(
            $this->getVariations()
        );
    }
}
