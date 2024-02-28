<?php

declare(strict_types=1);

namespace Gubee\Integration\Cron\Queue;

use Gubee\Integration\Helper\Config;
use Gubee\Integration\Model\Queue\Manager;

class Process
{
    protected Manager $queueManager;
    protected Config $config;

    public function __construct(
        Manager $queueManager,
        Config $config
    ) {
        $this->config       = $config;
        $this->queueManager = $queueManager;
    }

    public function execute()
    {
        if (! $this->config->isActive()) {
            return;
        }

        foreach ($this->queueManager->getPendingList() as $queueItem) {
            $this->queueManager->process($queueItem);
        }
    }
}
