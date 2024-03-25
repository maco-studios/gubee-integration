<?php

declare (strict_types = 1);

namespace Gubee\Integration\Command\Sales\Order\Processor;

use Exception;
use Gubee\Integration\Command\Sales\Order\AbstractProcessorCommand;
use Gubee\SDK\Resource\Sales\OrderResource;
use Magento\Framework\Event\ManagerInterface;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Status\HistoryFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Psr\Log\LoggerInterface;

class PayedCommand extends AbstractProcessorCommand {

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
        OrderManagementInterface $orderManagement,
        ?string $name = null
    ) {
        parent::__construct(
            $eventDispatcher,
            $logger,
            $orderResource,
            $orderCollectionFactory,
            $orderRepository,
            $historyFactory,
            $orderManagement,
            "payed"
        );
    }

    protected function doExecute(): int {
        return 0;
    }
}
