<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer\Sales\Order\Shipment\Save;

use Gubee\Integration\Command\Sales\Order\Shipment\SendCommand;
use Gubee\Integration\Observer\AbstractObserver;

class After extends AbstractObserver
{
    protected function process(): void
    {
        $shipment = $this->getObserver()->getShipment();
        $order    = $shipment->getOrder();

        $this->queueManagement->append(
            SendCommand::class,
            [
                'order_id' => $order->getIncrementId(),
            ]
        );
    }
}
