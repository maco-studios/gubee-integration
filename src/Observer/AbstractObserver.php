<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

use function sprintf;

abstract class AbstractObserver implements ObserverInterface
{
    protected LoggerInterface $logger;

    protected Observer $observer;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * Implement the logic of the observer
     */
    abstract public function process(): void;

    public function execute(Observer $observer): void
    {
        if (! $this->isAllowed()) {
            $this->getLogger()->debug(
                sprintf(
                    "Observer '%s' is not allowed to run",
                    static::class
                )
            );
            return;
        }

        $this->setObserver($observer);
        $this->getLogger()->debug(
            sprintf(
                "Observer '%s' is running",
                static::class
            )
        );
        $this->process();
        $this->getLogger()->debug(
            sprintf(
                "Observer '%s' has finished",
                static::class
            )
        );
    }

    /**
     * Check if the observer is allowed to run
     */
    abstract protected function isAllowed(): bool;

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function getObserver(): Observer
    {
        return $this->observer;
    }

    public function setObserver(Observer $observer): self
    {
        $this->observer = $observer;
        return $this;
    }
}
