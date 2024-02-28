<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer\Catalog\Product\Price\Save;

use Gubee\Integration\Command\Catalog\Product\Price\SendCommand;
use Gubee\Integration\Observer\Catalog\Product\AbstractProduct;

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
    protected function isAllowed(): bool
    {
        if (
            ! $this->getObserver()->getProduct()
            ->dataHasChangedFor(
                $this->config->getAttributePrice()
            )
        ) {
            return false;
        }

        return parent::isAllowed();
    }
}
