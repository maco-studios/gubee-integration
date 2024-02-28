<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer\Catalog\Product;

use Gubee\Integration\Observer\AbstractObserver;
use Gubee\Integration\Service\Hydration\Catalog\Eav\Attribute\AttributeTrait;

abstract class AbstractProduct extends AbstractObserver
{
    use AttributeTrait;

    /**
     * Check if the observer is allowed to run
     */
    protected function isAllowed(): bool
    {
        $product = $this->getObserver()->getProduct();
        if (
            $product->dataHasChangedFor('status')
            &&
            $this->getRawAttributeValue(
                $product,
                'gubee'
            )
        ) {
            return true;
        }

        if (
            ! $this->getRawAttributeValue(
                $product,
                'gubee_sync'
            ) && ! $product->dataHasChangedFor('gubee')
        ) {
            return false;
        }
        if (
            ! $this->getRawAttributeValue(
                $product,
                'gubee'
            )
        ) {
            return false;
        }

        return parent::isAllowed();
    }
}
