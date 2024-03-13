<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer\Catalog\Product\Attribute\Save;

use Gubee\Integration\Command\Catalog\Product\Attribute\SendCommand;
use Gubee\Integration\Observer\AbstractObserver;
use Magento\Catalog\Api\Data\EavAttributeInterface;

class After extends AbstractObserver
{
    protected function process(): void
    {
        $this->logger->info("Attribute saved");
        $this->getQueueManagement()->append(
            SendCommand::class,
            [
                "attribute" => $this->getObserver()->getObject()->getAttributeCode(), /** @phpstan-ignore-line */
            ]
        );
    }

    protected function isAllowed(): bool
    {
        return parent::isAllowed() &&
        $this->getObserver()->getObject() instanceof EavAttributeInterface; /** @phpstan-ignore-line */
    }
}
