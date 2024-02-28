<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer\Catalog\Product\Delete;

use Gubee\Integration\Command\Catalog\Product\Stock\DesativateCommand;
use Gubee\Integration\Observer\AbstractObserver;
use Gubee\Integration\Service\Hydration\Catalog\Eav\Attribute\AttributeTrait;

class After extends AbstractObserver
{
    use AttributeTrait;

    public function process(): void
    {
        $this->scheduleQueueItem(
            DesativateCommand::class,
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
        $product = $this->getObserver()->getProduct();
        if (
            ! $product->getGubee()
        ) {
            return false;
        }

        return true;
    }
}
