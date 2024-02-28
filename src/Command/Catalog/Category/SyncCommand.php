<?php

declare(strict_types=1);

namespace Gubee\Integration\Command\Catalog\Category;

use Gubee\Integration\Command\AbstractCommand;
use Gubee\Integration\Service\Model\Catalog\Category;
use Gubee\SDK\Resource\Catalog\CategoryResource;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Catalog\Model\ResourceModel\Category\Collection\Factory;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Psr\Log\LoggerInterface;

class SyncCommand extends AbstractCommand
{
    protected Collection $categoryCollection;
    protected CategoryResource $categoryResource;
    protected ObjectManagerInterface $objectManager;

    /**
     * @param string|null $name The name of the command; passing null means it must be set in configure()
     * @throws LogicException When the command name is empty.
     */
    public function __construct(
        Factory $categoryFactory,
        CategoryResource $categoryResource,
        ObjectManagerInterface $objectManager,
        ManagerInterface $eventDispatcher,
        LoggerInterface $logger,
        ?string $name = null
    ) {
        $this->categoryCollection = $categoryFactory->create();
        $this->categoryCollection = $this->categoryCollection
            ->addFieldToSelect('*');
        $this->eventDispatcher    = $eventDispatcher;
        $this->categoryResource   = $categoryResource;
        $this->objectManager      = $objectManager;
        $this->logger             = $logger;
        parent::__construct(
            $eventDispatcher,
            $logger,
            "catalog:category:sync"
        );
    }

    protected function configure(): void
    {
        $this->setDescription('Sync category to Gubee');
        $this->setHelp('This command allows you to sync category to Gubee');
    }

    /**
     * Executes the command.
     */
    protected function doExecute(): int
    {
        $categories = [];
        $this->categoryCollection->getSelect()
            ->order('level', 'ASC');
        foreach ($this->categoryCollection as $category) {
            $categories[] = $this->objectManager->create(
                Category::class,
                [
                    'category' => $category,
                ]
            );
        }

        $this->categoryResource->updateBulk($categories);
        return self::SUCCESS;
    }
}
