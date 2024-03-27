<?php

declare (strict_types = 1);

namespace Gubee\Integration\Observer\Catalog\Product\Attribute\Save;

use Gubee\Integration\Command\Catalog\Product\Attribute\SendCommand;
use Gubee\Integration\Model\Config;
use Gubee\Integration\Model\Queue\Management;
use Gubee\Integration\Model\ResourceModel\Catalog\Product\Attribute\CollectionFactory;
use Gubee\Integration\Observer\AbstractObserver;
use Psr\Log\LoggerInterface;

class After extends AbstractObserver {
    protected CollectionFactory $collectionFactory;

    public function __construct(
        Config $config,
        LoggerInterface $logger,
        Management $queueManagement,
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        parent::__construct(
            $config,
            $logger,
            $queueManagement
        );
    }

    protected function process(): void {
        $this->getQueueManagement()->append(
            SendCommand::class,
            [
                "attribute" => $this->getObserver()->getObject()->getAttributeCode(),
                /** @phpstan-ignore-line */
            ]
        );
    }

    protected function isAllowed(): bool {
        if ($this->getObserver()->getObject() instanceof \Magento\Catalog\Api\Data\EavAttributeInterface) {

            return true;
        }

        return false;
        // if (
        //     in_array(
        //         $this->getObserver()->getObject()->getAttributeCode() ?: [], /** @phpstan-ignore-line */
        //         $this->getConfig()->getBlacklistAttribute()
        //     )
        // ) {
        //     return false;
        // }

        // if (
        //     $this->getObserver()->getObject()->getAttributeCode() /** @phpstan-ignore-line */
        //     ===
        //     $this->getConfig()->getBrandAttribute()
        // ) {
        //     return false;
        // }

        // return parent::isAllowed(); /** @phpstan-ignore-line */
    }
}
