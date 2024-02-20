<?php

declare(strict_types=1);

namespace Gubee\Integration\Service;

use Gubee\Integration\Helper\Config;
use Gubee\SDK\Gubee;
use Gubee\SDK\Library\HttpClient\Builder;
use Psr\Log\LoggerInterface;

class Client extends Gubee
{
    public function __construct(
        Config $config,
        ?Builder $clientBuilder = null,
        ?LoggerInterface $logger = null
    ) {
        parent::__construct(
            $clientBuilder,
            $logger
        );
        $this->authenticate(
            $config->getApiToken()
        );
    }
}
