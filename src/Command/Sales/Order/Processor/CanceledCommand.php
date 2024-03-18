<?php

declare(strict_types=1);

namespace Gubee\Integration\Command\Sales\Order\Processor;

class CanceledCommand extends AbstractProcessorCommand
{
    protected function doExecute(): int
    {
        return 0;
    }
}
