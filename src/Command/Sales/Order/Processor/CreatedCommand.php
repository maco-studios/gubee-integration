<?php

declare(strict_types=1);

namespace Gubee\Integration\Command\Sales\Order\Processor;

use Gubee\Integration\Service\Model\Catalog\Product\Variation;
use Gubee\SDK\Resource\Sales\OrderResource;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Event\ManagerInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\QuoteManagement;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Model\Service\OrderService;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

use function __;
use function end;
use function explode;
use function hash;
use function microtime;
use function strpos;

class CreatedCommand extends AbstractProcessorCommand
{
    protected ProductRepositoryInterface $productRepository;
    protected QuoteManagement $quoteManagement;
    protected Context $context;
    protected StoreManagerInterface $storeManager;
    protected Product $product;
    protected FormKey $formkey;
    protected QuoteFactory $quote;
    protected CustomerFactory $customerFactory;
    protected CustomerRepositoryInterface $customerRepository;
    protected OrderService $orderService;

    public function __construct(
        ManagerInterface $eventDispatcher,
        LoggerInterface $logger,
        CollectionFactory $orderCollectionFactory,
        OrderResource $orderResource,
        QuoteManagement $quoteManagement,
        ProductRepositoryInterface $productRepository,
        Context $context,
        StoreManagerInterface $storeManager,
        Product $product,
        FormKey $formkey,
        OrderRepositoryInterface $orderRepository,
        QuoteFactory $quote,
        CustomerFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        OrderService $orderService
    ) {
        parent::__construct(
            $eventDispatcher,
            $logger,
            $orderResource,
            $orderCollectionFactory,
            $orderRepository,
            "created"
        );

        $this->context            = $context;
        $this->storeManager       = $storeManager;
        $this->product            = $product;
        $this->formkey            = $formkey;
        $this->orderRepository    = $orderRepository;
        $this->quote              = $quote;
        $this->customerFactory    = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->orderService       = $orderService;
        $this->productRepository  = $productRepository;
        $this->quoteManagement    = $quoteManagement;
    }

    protected function doExecute(): int
    {
        $orderId = $this->input->getArgument('order_id');
        $order   = $this->getOrder($orderId);
        if ($order != null) {
            return 0;
        }

        $this->logger->error(
            __(
                "Order with increment ID '%1' not found, creating it",
                $orderId
            )->__toString()
        );

        try {
            $this->logger->info(
                __("Creating order with increment ID '%1'", $orderId)->__toString()
            );
            $this->create($orderId);
            $this->logger->info(
                __("Order with increment ID '%1' created", $orderId)
            );
            return 0;
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());
            return 1;
        }
    }

    public function create(string $incrementId): bool
    {
        $gubeeOrder = $this->orderResource->loadByOrderId($incrementId);
        $customer   = $this->prepareCustomer($gubeeOrder);
        $quote      = $this->prepareQuote($gubeeOrder, $customer);
        return $this->persistOrder($quote, $customer, $gubeeOrder);
    }

    protected function persistOrder(
        CartInterface $quote,
        CustomerInterface $customer,
        array $gubeeOrder
    ) {
        $quote->collectTotals();
        $quote->setCustomer($customer);
        $quote->setCustomerIsGuest(false);
        $quote->setCustomerEmail($customer->getEmail());
        $quote->setCustomerFirstname($customer->getFirstname());
        $quote->setCustomerLastname($customer->getLastname());
        $quote->setCustomerGroupId($customer->getGroupId());
        $quote->setStoreId($this->storeManager->getStore()->getId());
        $quote->setIsActive(true);
        $quote->save();
        $quote->getPayment()->importData(['method' => 'checkmo']);
        $quote->collectTotals();
        $quote->save();
        $order = $this->quoteManagement->submit($quote);
        $order->setEmailSent(0);
        $order->save();

        $this->orderResource->updateOrder($gubeeOrder['id'], $order->getIncrementId());
        return true;
    }

    public function prepareCustomer(array $gubeeOrder): CustomerInterface
    {
        $customer = $this->customerFactory->create();
        $customer->setWebsiteId($this->storeManager->getWebsite()->getId());
        $customer->loadByEmail($gubeeOrder['customer']['email']);
        if (! $customer->getId()) {
            [$firstname, $lastname] = explode(' ', $gubeeOrder['customer']['name']);
            $customer->setEmail($gubeeOrder['customer']['email']);
            $customer->setFirstname($firstname);
            $customer->setLastname($lastname);
            $customer->setPassword(
                hash('sha256', $gubeeOrder['customer']['email'] . microtime())
            );
            $customer->save();
        }

        return $customer;
    }

    protected function prepareQuote(
        array $gubeeOrder,
        CustomerInterface $customer
    ) {
        $quote = $this->quoteFactory->create();
        $quote->assignCustomer($customer)
            ->setCurrency();
        $this->addItemsToQuote($gubeeOrder, $quote);
        return $quote;
    }

    protected function addItemsToQuote(array $gubeeOrder, CartInterface $quote)
    {
        foreach ($gubeeOrder['items'] as $item) {
            $product = $this->getProductByGubeeSku($item['sku']);
            $quote->addProduct($product, $item['qty']);
        }
    }

    protected function getProductByGubeeSku(
        string $sku
    ): ProductInterface {
        if (strpos($sku, Variation::SEPARATOR) !== false) {
            $sku = explode(Variation::SEPARATOR, $sku);
            $sku = end($sku);
        }

        $product = $this->productRepository->get($sku);
        if (! $product->getId()) {
            throw new Exception(
                __("Product with SKU '%1' not found", $sku)->__toString()
            );
        }

        return $product;
    }
}
