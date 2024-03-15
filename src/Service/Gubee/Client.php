<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Gubee;

use Gubee\SDK\Api\ServiceProviderInterface;
use Magento\Framework\App\ObjectManager;

class Client extends \Gubee\SDK\Client
{

    public function buildServiceProvider(): ServiceProviderInterface
    {
        return ObjectManager::getInstance()->get(ServiceProvider::class);
    }

}