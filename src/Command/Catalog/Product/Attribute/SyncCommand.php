<?php

declare(strict_types=1);

namespace Gubee\Integration\Command\Catalog\Product\Attribute;

use Gubee\Integration\Command\AbstractCommand;
use Gubee\Integration\Model\ResourceModel\Catalog\Attribute\CollectionFactory;
use Gubee\Integration\Service\Model\Catalog\Product\Attribute;
use Gubee\SDK\Gubee;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Psr\Log\LoggerInterface;

use function sprintf;

class SyncCommand extends AbstractCommand
{
    protected CollectionFactory $collectionFactory;
    protected ObjectManagerInterface $objectManager;
    protected Gubee $client;

    public function __construct(
        ManagerInterface $eventDispatcher,
        ObjectManagerInterface $objectManager,
        LoggerInterface $logger,
        CollectionFactory $collectionFactory,
        Gubee $client
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->objectManager     = $objectManager;
        $this->client            = $client;
        parent::__construct(
            $eventDispatcher,
            $logger,
            "catalog:product:attribute:sync"
        );
    }

    /**
     * Executes the command.
     */
    protected function doExecute(): int
    {
        $attributes = [];
        $collection = $this->collectionFactory->create();
        foreach ($collection->getItems() as $attribute) {
            $this->logger->info(
                sprintf(
                    "Synchronizing attribute '%s'",
                    $attribute->getAttributeCode()
                )
            );
            $attributes[] = $this->objectManager->create(
                Attribute::class,
                [
                    'eavAttribute' => $attribute,
                ]
            );
        }

        // echo json_encode($attributes, JSON_PRETTY_PRINT);
        // exit;

        $this->client->attribute()
            ->bulkUpdate($attributes);

        return self::SUCCESS;
    }
}
