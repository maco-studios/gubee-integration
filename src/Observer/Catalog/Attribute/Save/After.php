<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer\Catalog\Attribute\Save;

use Gubee\Integration\Command\Catalog\Product\Attribute\SendCommand;
use Gubee\Integration\Observer\AbstractObserver;
use Magento\Catalog\Api\Data\EavAttributeInterface;

class After extends AbstractObserver
{
    /**
     * Implement the logic of the observer
     */
    public function process(): void
    {
        $this->scheduleQueueItem(
            SendCommand::class,
            [
                'attribute_code' => $this->getObserver()
                    ->getObject()
                    ->getAttributeCode(),
            ]
        );
    }

    protected function isAllowed(): bool
    {
        $object = $this->getObserver()->getObject();
        if (! $object instanceof EavAttributeInterface) {
            return false;
        }

        if (
            $object->getAttributeCode() === $this->config->getAttributeBrand()
        ) {
            return false;
        }

        return parent::isAllowed();
    }
}
