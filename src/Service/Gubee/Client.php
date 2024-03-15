<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Gubee;

use Gubee\Integration\Model\Config;
use Gubee\SDK\Api\ServiceProviderInterface;
use DI\ContainerBuilder;
use Gubee\SDK\Library\HttpClient\Builder;
use Gubee\SDK\Library\HttpClient\Plugin\Authenticate;
use Gubee\SDK\Library\HttpClient\Plugin\Journal\History;
use Gubee\SDK\Library\ObjectManager\ServiceProvider;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\Plugin\HistoryPlugin;
use Http\Client\Common\Plugin\RetryPlugin;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Magento\Framework\App\ObjectManager;

class Client extends \Gubee\SDK\Client
{

    public function __construct(
        Config $config,
        ?ServiceProviderInterface $serviceProvider = null,
        ?LoggerInterface $logger = null,
        ?Builder $httpClientBuilder = null,
        int $retryCount = 3
    )
    {
        parent::__construct($serviceProvider, $logger, $httpClientBuilder, $retryCount);
        $this->authenticate($config->getApiToken());
    }

    public function buildServiceProvider(): ServiceProviderInterface
    {
        return ObjectManager::getInstance()->get(ServiceProviderInterface::class);
    }
}
