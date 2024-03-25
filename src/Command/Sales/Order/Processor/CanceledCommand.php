<?php

declare (strict_types = 1);

namespace Gubee\Integration\Command\Sales\Order\Processor;

use Gubee\Integration\Command\Sales\Order\AbstractProcessorCommand;
use Gubee\SDK\Resource\Sales\OrderResource;
use Magento\Framework\Event\ManagerInterface;
use Magento\Sales\Api\InvoiceManagementInterface;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Status\HistoryFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Exception\LogicException;

class CanceledCommand extends AbstractProcessorCommand {

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
        InvoiceManagementInterface $invoiceManagement,
        ?string $name = null
    ) {
        $this->invoiceManagement = $invoiceManagement;
        parent::__construct(
            $eventDispatcher,
            $logger,
            $orderResource,
            $orderCollectionFactory,
            $orderRepository,
            $historyFactory,
            $orderManagement,
            "canceled"
        );
    }
    protected function doExecute(): int {
        $order = $this->getOrder(
            $this->input->getArgument('order_id')
        );
        /** check if order is aready cancelled */
        if ($order->getState() === 'canceled') {
            $this->logger->info(
                sprintf(
                    "Order with ID %s is already canceled",
                    $order->getId()
                )
            );
            return 0;
        }

        $this->cancelOrder($order);

        return 0;
    }

    private function cancelOrder($order): void {
        try {
            // cancel invoices
            $invoices = $order->getInvoiceCollection();
            foreach ($invoices as $invoice) {
                $this->invoiceManagement
                    ->cancel($invoice->getId())
                    ->save();
            }
            // cancel order

            $this->orderManagement
                ->cancel($order->getId())
                ->save();
            $this->addOrderHistory(
                __('Order canceled!')->__toString(),
                (int) $order->getId()
            );
            $this->logger->info(
                sprintf(
                    "Order with ID %s canceled",
                    (string) $order->getId()
                )
            );
        } catch (\Throwable $e) {
            $this->logger->error(
                sprintf(
                    "Error canceling order with ID %s: %s",
                    $order->getId(),
                    $e->getMessage()
                )
            );
        }
    }
}
