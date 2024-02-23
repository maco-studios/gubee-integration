<?php

declare(strict_types=1);

namespace Gubee\Integration\Service;

use Gubee\Integration\Helper\Config;
use Gubee\SDK\Gubee;
use Gubee\SDK\Library\HttpClient\Builder;
use Psr\Log\LoggerInterface;

use function sprintf;

class Client extends Gubee
{
    public function __construct(
        Config $config,
        ?Builder $clientBuilder = null,
        ?LoggerInterface $logger = null
    ) {
        $logger->debug(
            sprintf(
                'Creating Gubee client with API token: %s',
                $config->getApiToken()
            )
        );
        parent::__construct($clientBuilder, $logger);
        $this->authenticate($config->getApiToken());
        $logger->debug('Gubee client created');
    }
}
