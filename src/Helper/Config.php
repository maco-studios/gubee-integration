<?php

declare(strict_types=1);

namespace Gubee\Integration\Helper;

use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject;

use function sprintf;

class Config extends AbstractHelper
{
    public const CONFIG_PATH = 'gubee/integration';

    protected DataObject $config;
    protected WriterInterface $configWriter;
    protected ReinitableConfigInterface $reinitableConfig;

    public function __construct(
        Context $context,
        WriterInterface $configWriter,
        ReinitableConfigInterface $reinitableConfig
    ) {
        parent::__construct($context);
        $this->reinitableConfig = $reinitableConfig;
        $this->configWriter     = $configWriter;
        $this->config           = new DataObject(
            $this->scopeConfig->getValue(
                self::CONFIG_PATH
            ) ?: []
        );
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
