<?php

declare(strict_types=1);

namespace Gubee\Integration\Helper;

use DateTimeInterface;
use Gubee\Integration\Api\Data\LogInterface;
use Gubee\Integration\Command\Gubee\Token\RenewCommand;
use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Stdlib\DateTime;
use Psr\Log\LoggerInterface;

use function array_merge;
use function explode;
use function sprintf;

class Config extends AbstractHelper
{
    public const CONFIG_PATH = 'gubee/integration';

    protected DataObject $config;
    protected WriterInterface $configWriter;
    protected ReinitableConfigInterface $reinitableConfig;
    protected ObjectManagerInterface $objectManager;
    protected LoggerInterface $logger;

    public function __construct(
        Context $context,
        WriterInterface $configWriter,
        ReinitableConfigInterface $reinitableConfig,
        LoggerInterface $logger,
        ObjectManagerInterface $objectManager
    ) {
        parent::__construct($context);
        $this->objectManager    = $objectManager;
        $this->logger           = $logger;
        $this->reinitableConfig = $reinitableConfig;
        $this->configWriter     = $configWriter;
        $this->config           = new DataObject(
            $this->scopeConfig->getValue(
                self::CONFIG_PATH
            ) ?: []
        );
    }

    public function load(): self
    {
        $this->config = new DataObject(
            $this->scopeConfig->getValue(
                self::CONFIG_PATH
            ) ?: []
        );
        return $this;
    }

    public function save(): self
    {
        if (
            $this->getConfig()
                ->getOrigData()
            ===
            $this->getConfig()
                ->getData()
        ) {
            return $this;
        }

        foreach ($this->getConfig()->getData() as $key => $value) {
            $this->configWriter->save(
                sprintf(
                    "%s/%s",
                    self::CONFIG_PATH,
                    $key
                ),
                $value
            );
        }

        $this->reinitableConfig->reinit();
        return $this;
    }

    public function getApiToken(): mixed
    {
        if (! $this->getConfig()->getActive()) {
            return $this;
        }

        if ($this->isTokenExpired()) {
            $input    = $this->objectManager->create(
                ArrayInput::class,
                [
                    'parameters' => [
                        'token' => $this->getConfig()->getApiKey(),
                    ],
                ]
            );
            $output   = $this->objectManager->create(BufferedOutput::class);
            $renewCmd = $this->objectManager->create(
                RenewCommand::class,
                [
                    'config' => $this,
                ]
            );
            $renewCmd->run($input, $output);
            $this->load();
        }

        return $this->getConfig()
            ->getApiToken();
    }

    public function isTokenExpired(): bool
    {
        $expirationDate = DateTime::createFromFormat(
            DateTimeInterface::ISO8601,
            $this->getConfig()
                ->getApiTimeout()
        )->getTimestamp();
        $date           = new DateTime();
        return $date->getTimestamp() > $expirationDate;
    }

    public function getLogLevel(): array
    {
        return array_merge(
            [
                LogInterface::ERROR,
            ],
            explode(
                ',',
                $this->config->getLogLevel() ?: ''
            ) ?: []
        );
    }

    /**
     * Call methods from DataObject
     *
     * @param string $name
     * @param array  $arguments
     * @return mixed
     */
    public function __call(
        $name,
        $arguments
    ) {
        return $this->config->$name(...$arguments);
    }

    public function getConfig(): DataObject
    {
        return $this->config;
    }
}
