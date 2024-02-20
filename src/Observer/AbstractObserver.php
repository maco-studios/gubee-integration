<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer;

use Gubee\Integration\Helper\Config;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

use function sprintf;

abstract class AbstractObserver implements ObserverInterface
{
    protected LoggerInterface $logger;

    protected Observer $observer;
    protected Config $config;

    public function __construct(
        LoggerInterface $logger,
        Config $config
    ) {
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * Implement the logic of the observer
     */
    abstract public function process(): void;

    public function execute(Observer $observer): void
    {
        $this->setObserver($observer);

        if (! $this->isAllowed()) {
            $this->getLogger()->debug(
                sprintf(
                    "Observer '%s' is not allowed to run",
                    static::class
                )
            );
            return;
        }

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
    protected function isAllowed(): bool {
        if (!$this->getConfig()->getActive()) {
            return false;
        }

        return true;
    }

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

	/**
	 * @return Config
	 */
	public function getConfig(): Config {
		return $this->config;
	}
}
