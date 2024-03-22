<?php

declare(strict_types=1);

namespace Gubee\Integration\Command\Sales\Order\Processor;

use Gubee\Integration\Command\Sales\Order\AbstractProcessorCommand;

class PaidCommand extends AbstractProcessorCommand
{
    protected function doExecute(): int
    {
        return 0;
    }
}
