<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer\Catalog\Product\Save;

use Gubee\Integration\Command\Catalog\Product\SendCommand;
use Gubee\Integration\Observer\Catalog\Product\AbstractProduct;

class Desativate extends AbstractProduct
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

    protected function isAllowed(): bool
    {
        return parent::isAllowed() && $this->getObserver()
            ->getProduct()
            ->getStatus() === 2;
    }
}
