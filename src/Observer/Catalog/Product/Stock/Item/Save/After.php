<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer\Catalog\Product\Stock\Item\Save;

use Gubee\Integration\Command\Catalog\Product\Stock\SendCommand;
use Gubee\Integration\Observer\Catalog\Product\AbstractProduct;

use function json_decode;
use function json_encode;

class After extends AbstractProduct
{
    public function process(): void
    {
        $this->scheduleQueueItem(
            SendCommand::class,
            [
                'sku' => $this->getObserver()->getProduct()->getSku(),
            ]
        );
    }

    /**
     * Check if the observer is allowed to run
     */
    //phpcs:disable
    protected function isAllowed(): bool
    {
        $product  = $this->getObserver()->getProduct();
        $origJson = json_decode(
            json_encode(
                $product->getOrigData()
            ),
            true
        );
        if (isset($origJson['quantity_and_stock_status'])) {
            if (
                $origJson['quantity_and_stock_status']['qty']
                ==
                $product->getData('stock_data/qty')
                &&
                $origJson['quantity_and_stock_status']['is_in_stock']
                ==
                $product->getData('stock_data/is_in_stock')
            ) {
                return false;
            }
        }

        return parent::isAllowed();
    }
    //phpcs:enable
}
