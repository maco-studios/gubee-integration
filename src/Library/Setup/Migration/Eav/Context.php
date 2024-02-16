<?php

declare(strict_types=1);

namespace Gubee\Integration\Library\Setup\Migration\Eav;

use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\App\ResourceConnection;

class Context
{
    protected Config $config;

    protected EavSetupFactory $eavSetupFactory;

    protected ResourceConnection $resourceConnection;

    public function __construct(
        Config $config,
        EavSetupFactory $eavSetupFactory,
        ResourceConnection $resourceConnection
    ) {
        $this->config             = $config;
        $this->eavSetupFactory    = $eavSetupFactory;
        $this->resourceConnection = $resourceConnection;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function getEavSetupFactory(): EavSetupFactory
    {
        return $this->eavSetupFactory;
    }

    public function getResourceConnection(): ResourceConnection
    {
        return $this->resourceConnection;
    }
}
