<?php

declare (strict_types = 1);

namespace Gubee\Integration\Command\Sales\Order\Invoice;

use Gubee\Integration\Api\Data\InvoiceInterface;
use Gubee\Integration\Api\InvoiceRepositoryInterface;
use Gubee\Integration\Command\Sales\Order\AbstractProcessorCommand;
use Gubee\SDK\Resource\Sales\OrderResource;
use InvalidArgumentException;
use Magento\Framework\Event\ManagerInterface;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Status\HistoryFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputArgument;

class SendCommand extends AbstractProcessorCommand {
    protected InvoiceRepositoryInterface $invoiceRepository;

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
        InvoiceRepositoryInterface $invoiceRepository
    ) {
        $this->invoiceRepository = $invoiceRepository;
        parent::__construct(
            $eventDispatcher,
            $logger,
            $orderResource,
            $orderCollectionFactory,
            $orderRepository,
            $historyFactory,
            $orderManagement,
            "invoice:send",
        );
    }

    protected function configure() {
        $this->addArgument('invoice_id', InputArgument::REQUIRED, 'Invoice ID');
    }

    protected function beforeExecute($input, $output) {
        /**
         * This command is only called by the magento internal process,
         * so we don't need to check if the order is already existent.
         */
    }

    protected function doExecute(): int {
        $invoice = $this->getInvoice();
        if ($invoice->getOrigin() === InvoiceInterface::ORIGIN_GUBEE) {
            throw new InvalidArgumentException(
                __(
                    "The invoice '%1' is already on Gubee",
                    $invoice->getInvoiceId()
                )
            );
        }
        $order = $this->orderRepository->get($invoice->getOrderId());

        $this->orderResource->updateInvoiced(
            $order->getIncrementId(),
            $invoice->jsonSerialize()
        );
        return 0;
    }

    private function getInvoice(): InvoiceInterface {
        $invoiceId = $this->input->getArgument('invoice_id');
        return $this->invoiceRepository->get($invoiceId);
    }
}
