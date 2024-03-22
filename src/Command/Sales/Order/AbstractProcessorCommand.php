<?php

declare(strict_types=1);

namespace Gubee\Integration\Command\Sales\Order;

use Gubee\Integration\Command\AbstractCommand;
use Gubee\SDK\Resource\Sales\OrderResource;
use Magento\Framework\Event\ManagerInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Status\HistoryFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputArgument;

use function sprintf;

abstract class AbstractProcessorCommand extends AbstractCommand
{
    protected OrderRepositoryInterface $orderRepository;
    protected CollectionFactory $orderCollectionFactory;
    protected OrderResource $orderResource;
    protected HistoryFactory $historyFactory;

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
        ?string $name = null
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->orderRepository        = $orderRepository;
        $this->orderResource          = $orderResource;
        $this->historyFactory         = $historyFactory;
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

    public function getOrder(string $incrementId): ?OrderInterface
    {
        $order = $this->orderCollectionFactory->create()
            ->addFieldToFilter('increment_id', $incrementId)
            ->getFirstItem();

        if (! $order->getId()) {
            return null;
        }

        return $order;
    }

    public function addOrderHistory(string $message, int $orderId)
    {
        $history = $this->historyFactory->create();
        $history->setComment(
            sprintf("[Gubee Integration] %s", (string) $message)
        );
        $history->setParentId($orderId);
        $history->setIsCustomerNotified(false);
        $history->setIsVisibleOnFront(true);
        $history->save();
    }
}
