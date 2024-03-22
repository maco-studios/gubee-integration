<?php

declare(strict_types=1);

namespace Gubee\Integration\Command\Sales\Order\Processor;

use Gubee\Integration\Command\Sales\Order\AbstractProcessorCommand;

class CanceledCommand extends AbstractProcessorCommand
{
    protected function doExecute(): int
    {
        $order = $this->getOrder();
        /** check if order is aready cancelled */
        if ($order->isCanceled()) {
            return 0;
        }

        $this->cancelOrder($order);

        return 0;
    }

    private function cancelOrder(Order $order): void
    {
        $order->cancel();
        // add update comment to order
        $history = $this->historyFactory->create();
        $history->setComment('Order was cancelled by Gubee');
        $history->setParentId($order->getId());
        $history->setIsCustomerNotified(false);
        $history->setIsVisibleOnFront(true);
        $history->save();
    }
}
