<?php

declare(strict_types=1);

namespace Gubee\Integration\Command\Sales\Order\Shipment;

use DateTime;
use Gubee\Integration\Command\Sales\Order\AbstractProcessorCommand;
use Gubee\Integration\Model\Config;
use Gubee\SDK\Resource\Sales\OrderResource;
use Magento\Framework\Event\ManagerInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Status\HistoryFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Exception\LogicException;

use function print_r;

class SendCommand extends AbstractProcessorCommand
{
    protected Config $config;

    /**
     * @param string|null $name The name of the command; passing null means it must be set in configure()
     * @throws LogicException When the command name is empty.
     */
    public function __construct(
        ManagerInterface $eventDispatcher,
        LoggerInterface $logger,
        OrderResource $orderResource,
        CollectionFactory $orderCollectionFactory,
        OrderRepositoryInterface $orderRepository,
        HistoryFactory $historyFactory,
        Config $config
    ) {
        $this->config = $config;
        parent::__construct(
            $eventDispatcher,
            $logger,
            $orderResource,
            $orderCollectionFactory,
            $orderRepository,
            $historyFactory,
            "shipment:send"
        );
    }

    protected function doExecute(): int
    {
        $orderId = $this->getInput()->getArgument('order_id');
        $order   = $this->getOrder($orderId);

        // $this->orderResource->updateShipped(
        //     $order->getIncrementId(),
        //     [
        //         'code' => $order->getShippingMethod(),
        //         'estimatedDeliveryDt' => new \DateTime(
        //             'now + '
        //             . $this->config->getDefaultDeliveryTime()
        //             . ' days'
        //         ),
        //         'transport' => [
        //             'carrier' => $order->getShippingDescription(),
        //             'link' => $order->getShippingTracking(),
        //             'method' => $order->getShippingMethod(),
        //         ],
        //     ]
        // );

        // get all tracking numbers
        $trackings = $order->getTracksCollection();
        foreach ($trackings as $tracking) {
            print_r($tracking->getData());
            print_r(
                [
                    'code'                => $order->getShippingMethod(),
                    'estimatedDeliveryDt' => new DateTime(
                        'now + '
                        . $this->config->getDefaultDeliveryTime()
                        . ' days'
                    ),
                    'transport'           => [
                        'carrier'      => $tracking->getTitle(),
                        'link'         => $order->getShippingTracking(),
                        'method'       => $order->getShippingMethod(),
                        'trackingCode' => $tracking->getTrackNumber(),
                    ],
                ]
            );
        }
        exit;

        exit;

        return 0;
    }
}
