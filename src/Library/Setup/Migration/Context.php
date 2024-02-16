<?php

declare(strict_types=1);

namespace Gubee\Integration\Library\Setup\Migration;

use Magento\Framework\App\State;
use Magento\Framework\Setup\ModuleDataSetupInterface as ModuleDataSetup;
use Magento\Framework\Setup\Patch\PatchHistory;
use Psr\Log\LoggerInterface;

class Context
{
    protected ModuleDataSetup $moduleDataSetup;
    protected LoggerInterface $logger;
    protected PatchHistory $patchHistory;
    protected State $state;

    public function __construct(
        ModuleDataSetup $moduleDataSetup,
        LoggerInterface $logger,
        PatchHistory $patchHistory,
        State $state
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->logger          = $logger;
        $this->patchHistory    = $patchHistory;
        $this->state           = $state;
    }

    public function getModuleDataSetup(): ModuleDataSetupInterface
    {
        return $this->moduleDataSetup;
    }

    public function setModuleDataSetup(ModuleDataSetupInterface $moduleDataSetup): self
    {
        $this->moduleDataSetup = $moduleDataSetup;
        return $this;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;
        return $this;
    }

    public function getPatchHistory(): PatchHistory
    {
        return $this->patchHistory;
    }

    public function setPatchHistory(PatchHistory $patchHistory): self
    {
        $this->patchHistory = $patchHistory;
        return $this;
    }

    public function getState(): State
    {
        return $this->state;
    }

    public function setState(State $state): self
    {
        $this->state = $state;
        return $this;
    }
}
