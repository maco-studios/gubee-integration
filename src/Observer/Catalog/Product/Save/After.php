<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer\Catalog\Product\Save;

use Gubee\Integration\Command\Catalog\Product\SendCommand;
use Gubee\Integration\Observer\Catalog\Product\AbstractProduct;

class After extends AbstractProduct
{
    protected function process(): void
    {
        $this->queueManagement->append(
            SendCommand::class,
            [
                'sku' => $this->getProduct()->getSku(),
            ],
            (int) $this->getProduct()->getId()
        );
    }
}
