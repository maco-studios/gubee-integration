<?php

declare(strict_types=1);

namespace Gubee\Integration\Command\Sales\Order\Processor;

use Exception;
use Gubee\Integration\Command\AbstractCommand;
use Magento\Framework\Event\ManagerInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputArgument;

abstract class AbstractProcessorCommand extends AbstractCommand
{
    protected OrderRepositoryInterface $orderRepository;
    protected CollectionFactory $orderCollectionFactory;

    /**
     * @param string|null $name The name of the command; passing null means it must be set in configure()
     * @throws LogicException When the command name is empty.
     */
    public function __construct(
        ManagerInterface $eventDispatcher,
        LoggerInterface $logger,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        ?string $name = null
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        parent::__construct(
            $eventDispatcher,
            $logger,
            "sales:order:processor:" . $name
        );
    }

    protected function configure()
    {
        $this->addArgument('order_id', InputArgument::REQUIRED, 'Order increment ID');
    }

    public function getOrder(string $incrementId): OrderInterface
    {
        $order = $this->orderCollectionFactory->create()
            ->addFieldToFilter('increment_id', $incrementId)
            ->getFirstItem();

        if (! $order->getId()) {
            throw new Exception("Order not found");
        }

        return $order;
    }
}
