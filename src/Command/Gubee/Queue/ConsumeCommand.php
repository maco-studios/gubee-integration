<?php

declare(strict_types=1);

namespace Gubee\Integration\Command\Gubee\Queue;

use Gubee\Integration\Command\AbstractCommand;
use Gubee\Integration\Model\Message\Management;
use Magento\Framework\Event\ManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Exception\LogicException;

class ConsumeCommand extends AbstractCommand
{
    protected Management $queueManagement;

    /**
     * @param string|null $name The name of the command; passing null means it must be set in configure()
     * @throws LogicException When the command name is empty.
     */
    public function __construct(
        ManagerInterface $eventDispatcher,
        LoggerInterface $logger,
        Management $queueManagement
    ) {
        $this->queueManagement = $queueManagement;
        parent::__construct($eventDispatcher, $logger, 'queue:consume');
    }

    protected function doExecute(): int
    {
        $this->logger->info("Processing queue");
        foreach ($this->queueManagement->getPending()->getItems() as $message) {
            echo "Processing message {$message->getId()}\n";
            $this->queueManagement->process($message);
        }

        $this->logger->info("Queue processed");
        return 0;
    }
}
