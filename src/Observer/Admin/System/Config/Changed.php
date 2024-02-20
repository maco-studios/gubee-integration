<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer\Admin\System\Config;

use Gubee\Integration\Command\Gubee\Token\RenewCommand;
use Gubee\Integration\Helper\Config;
use Gubee\Integration\Model\Queue\Manager;
use Gubee\Integration\Observer\AbstractObserver;
use Magento\Framework\ObjectManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

use function in_array;

class Changed extends AbstractObserver
{
    protected RenewCommand $renewCommand;
    protected ObjectManagerInterface $objectManager;

    public function __construct(
        Manager $queueManager,
        LoggerInterface $logger,
        Config $config,
        RenewCommand $renewCommand,
        ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
        $this->renewCommand  = $renewCommand;
        parent::__construct(
            $queueManager,
            $logger,
            $config
        );
    }

    public function process(): void
    {
        $input  = $this->objectManager->create(
            ArrayInput::class,
            [
                'parameters' => [
                    'token' => $this->getConfig()->getApiKey(),
                ],
            ]
        );
        $output = $this->objectManager->create(BufferedOutput::class);
        $this->renewCommand->run(
            $input,
            $output
        );
    }

    protected function isAllowed(): bool
    {
        $apiKeyHasChanged = in_array(
            'gubee/integration/active',
            $this->getObserver()->getData(
                'changed_paths'
            )
        ) || in_array(
            'gubee/integration/api_key',
            $this->getObserver()->getData(
                'changed_paths'
            )
        );

        $isAllowed = parent::isAllowed();
        return $isAllowed && $apiKeyHasChanged;
    }
}
