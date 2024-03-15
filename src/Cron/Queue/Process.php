<?php

declare(strict_types=1);

namespace Gubee\Integration\Cron\Queue;

use Gubee\Integration\Model\Config;
use Magento\Framework\ObjectManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class Process
{
    protected Management $queueManagement;
    protected LoggerInterface $logger;
    protected ObjectManagerInterface $objectManager;
    protected Config $config;
    public function __construct(
        LoggerInterface $logger,
        ObjectManagerInterface $objectManager,
        Config $config
    ) {
        $this->objectManager = $objectManager;
        $this->config        = $config;
        $this->logger        = $logger;
    }

    public function execute(): int
    {
        if (! $this->isAllowed()) {
            return 0;
        }

        $this->logger->info("Processing queue");
        $input   = $this->objectManager->create(ArrayInput::class);
        $output  = $this->objectManager->create(BufferedOutput::class);
        $command = $this->objectManager->create(ConsumeCommand::class);
        $command->run($input, $output);
        $this->logger->info("Queue processed");
        return 0;
    }

    protected function isAllowed(): bool
    {
        return $this->config->getActive();
    }
}
