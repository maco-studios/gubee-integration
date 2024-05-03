<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer\Sales\Order\Cancel;

use Gubee\Integration\Api\OrderRepositoryInterface;
use Gubee\Integration\Command\Sales\Order\Cancel\SendCommand;
use Gubee\Integration\Model\Config;
use Gubee\Integration\Model\Queue\Management;
use Gubee\Integration\Observer\AbstractObserver;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class After extends AbstractObserver
{
    protected OrderRepositoryInterface $orderRepository;

    public function __construct(
        Config $config,
        LoggerInterface $logger,
        Management $queueManagement,
        OrderRepositoryInterface $orderRepository
    ) {
        parent::__construct($config, $logger, $queueManagement);
        $this->orderRepository = $orderRepository;
    }

    protected function process(): void
    {
        $order = $this->getObserver()->getEvent()->getOrder();
        try {
            $order = $this->orderRepository->getByOrderId($order->getId());
            
            $this->queueManagement->append(
                SendCommand::class,
                [
                    'order_id' => $order->getGubeeOrderId(),
                ]
            );
        }
        catch (NoSuchEntityException $exception)
        {
            $this->logger->info("Order {$order->getId()} is not integrated with gubee");
        }
    }
}
