<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer\Adminhtml\System\Config\Gubee;

use Gubee\Integration\Command\Gubee\Token\RenewCommand;
use Gubee\Integration\Model\Config;
use Gubee\Integration\Observer\AbstractObserver;
use Magento\Framework\App\Cache\Frontend\Pool;
use Magento\Framework\App\Cache\TypeListInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

use function __;
use function in_array;

class OnChange extends AbstractObserver
{
    protected TypeListInterface $typeList;
    protected Pool $pool;
    protected ArrayInput $arrayInput;
    protected BufferedOutput $bufferedOutput;
    protected RenewCommand $renewCommand;

    public function __construct(
        Config $config,
        LoggerInterface $logger,
        TypeListInterface $typeList,
        ArrayInput $arrayInput,
        BufferedOutput $bufferedOutput,
        RenewCommand $renewCommand,
        Pool $pool
    ) {
        parent::__construct($config, $logger);
        $this->typeList       = $typeList;
        $this->renewCommand   = $renewCommand;
        $this->arrayInput     = $arrayInput;
        $this->bufferedOutput = $bufferedOutput;
        $this->pool           = $pool;
    }

    protected function process(): void
    {
        $this->getLogger()->debug(
            __(
                "The Gubee Integration configuration has been changed."
            )->__toString()
        );

        $changedPaths = $this->getObserver()
            ->getData('changed_paths');
        if (in_array('gubee/general/active', $changedPaths)) {
            $this->getLogger()->debug(
                __(
                    "The Gubee Integration active status has been changed."
                )->__toString()
            );
            $this->clearMenuCache();
        }

        /**
         * If the api key has changed we need to generate a new token
         * and save it in the configuration
         */
        if (in_array('gubee/general/api_key', $changedPaths)) {
            $this->getLogger()->debug(
                __(
                    "The Gubee Integration API Key has been changed."
                )->__toString()
            );
            $this->generateToken();
        }
    }

    protected function generateToken(): void
    {
        $this->getArrayInput()->setArgument(
            'token',
            $this->getConfig()->getApiKey()
        );
        $this->renewCommand->run(
            $this->getArrayInput(),
            $this->getBufferedOutput()
        );
        $this->getLogger()->debug(
            __(
                "The Gubee Integration token has been renewed."
            )->__toString()
        );
    }

    protected function clearMenuCache(): void
    {
        $types = [
            'layout',
            'full_page',
            'translate',
        ];
        foreach ($types as $type) {
            $this->getTypeList()
                ->cleanType($type);
        }
        foreach ($this->getPool() as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
        $this->getLogger()->debug(
            __(
                "The Gubee Integration menu cache has been cleared."
            )->__toString()
        );
    }

    protected function isAllowed(): bool
    {
        return true;
    }

    public function getTypeList(): TypeListInterface
    {
        return $this->typeList;
    }

    public function getPool(): Pool
    {
        return $this->pool;
    }

    public function getArrayInput(): ArrayInput
    {
        return $this->arrayInput;
    }

    public function getBufferedOutput(): BufferedOutput
    {
        return $this->bufferedOutput;
    }
}
