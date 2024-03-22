<?php

declare(strict_types=1);

namespace Gubee\Integration\Command\Sales\Order\Processor;

use Gubee\Integration\Command\Sales\Order\AbstractProcessorCommand;

class DeliveredCommand extends AbstractProcessorCommand
{
    protected function doExecute(): int
    {
        $order = $this->getOrder();

        $this->deliverOrder($order);

        return 0;
    }

    private function deliverOrder(Order $order): void
    {
        // set order as completed
        $order->setState(Order::STATE_COMPLETE);
        $order->setStatus(Order::STATE_COMPLETE);
        $order->save();
        $this->addOrderHistory(
            "Order was delivered!",
        );
    }
}
