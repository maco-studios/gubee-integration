<?php

declare(strict_types=1);

namespace Gubee\Integration\Command\Sales\Order\Processor;

use Exception;
use Gubee\Integration\Command\Sales\Order\AbstractProcessorCommand;
use Gubee\Integration\Service\Model\Catalog\Product\Variation;
use Gubee\SDK\Resource\Sales\OrderResource;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Directory\Model\Country;
use Magento\Directory\Model\Region;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\QuoteManagement;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Status\HistoryFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Model\Service\OrderService;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

use function __;
use function end;
use function explode;
use function hash;
use function implode;
use function microtime;
use function print_r;
use function sizeof;
use function sprintf;
use function strpos;

class CreatedCommand extends AbstractProcessorCommand
{
    protected ProductRepositoryInterface $productRepository;
    protected QuoteManagement $quoteManagement;
    protected Context $context;
    protected StoreManagerInterface $storeManager;
    protected Product $product;
    protected FormKey $formkey;
    protected QuoteFactory $quoteFactory;
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
        QuoteFactory $quoteFactory,
        CustomerFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        OrderService $orderService,
        HistoryFactory $historyFactory
    ) {
        parent::__construct(
            $eventDispatcher,
            $logger,
            $orderResource,
            $orderCollectionFactory,
            $orderRepository,
            $historyFactory,
            "created"
        );
        $this->context            = $context;
        $this->storeManager       = $storeManager;
        $this->product            = $product;
        $this->formkey            = $formkey;
        $this->orderRepository    = $orderRepository;
        $this->quoteFactory       = $quoteFactory;
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
            $this->logger->error(
                __("Error creating order with increment ID '%1'", $orderId)
            );
            throw $e;
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
        $shippingAddress = $this->createAddress(
            new DataObject($gubeeOrder['shippingAddress']),
            $gubeeOrder['customer'],
            $customer->getId()
        );
        $billingAddress  = $this->createAddress(
            new DataObject($gubeeOrder['billingAddress']),
            $gubeeOrder['customer'],
            $customer->getId()
        );
        /**
         * Set flag to not collect and recalculate totals
         */
        $quote->setCustomer($customer);
        $quote->setCustomerIsGuest(false);
        $quote->setCustomerEmail($customer->getEmail());
        $quote->setCustomerFirstname($customer->getFirstname());
        $quote->setCustomerLastname($customer->getLastname());
        $quote->setCustomerGroupId($customer->getGroupId());
        $quote->setStoreId($this->storeManager->getStore()->getId());
        $quote->setIsActive(true);
        $quote->getBillingAddress()->addData($billingAddress);
        $quote->getShippingAddress()->addData($shippingAddress);
        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod(
                'gubee_gubee'
            );
        $quote->setPaymentMethod('gubee');
        $quote->setInventoryProcessed(true);
        $quote->save();

        $paymentData = [
            'method' => 'gubee',
        ];
        foreach ($gubeeOrder['payments'] as $payment) {
            $paymentData['additional_data']['payment'][] = $payment;
        }

        $quote->getPayment()->importData(
            $paymentData
        );
        $shippingAmount = $gubeeOrder['totalFreight'];
        $quote->setTotalsCollectedFlag(false);
        $quote->collectTotals()->save();
        $externalId = $gubeeOrder['id'];
        $order      = $this->quoteManagement->submit($quote);
        $order->setShippingAmount($shippingAmount);
        $order->setBaseShippingAmount($shippingAmount);

        $order->setEmailSent(0);
        $order->setIncrementId($externalId);
        $order->save();
        $this->addOrderHistory(
            __("Order created by Gubee Integration")
        );

        return true;
    }

    public function prepareCustomer(array $gubeeOrder): CustomerInterface
    {
        try {
            $customer = $this->customerRepository->get(
                $gubeeOrder['customer']['email'],
                $this->storeManager->getWebsite()->getId()
            );
        } catch (Exception $e) {
            $customer = $this->customerFactory->create();
        }
        if (! $customer->getId()) {
            [$firstname, $lastname] = explode(' ', $gubeeOrder['customer']['name']);
            $customer->setEmail($gubeeOrder['customer']['email']);
            $customer->setTaxvat(
                $gubeeOrder['customer']['documents'][0]['number']
            );
            $customer->setFirstname($firstname);
            $customer->setLastname($lastname);
            $customer->setTaxvat($gubeeOrder['customer']['documents'][0]['number']);
            $customer->setPassword(
                hash('sha256', $gubeeOrder['customer']['email'] . microtime())
            );
            $customer->save();
            $customer = $this->customerRepository->get(
                $gubeeOrder['customer']['email'],
                $this->storeManager->getWebsite()->getId()
            );
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
        echo '<pre>';
        print_r($gubeeOrder);
        exit;
        foreach ($gubeeOrder['items'] as $item) {
            try {
                $product = $this->getProductByGubeeSku(
                    isset($item['subItems']) ? $item['subItems'][0]['skuId'] : $item['skuId']
                );
                if (! $product->getId()) {
                    throw new Exception(
                        __("Product with SKU '%1' not found", isset($item['subItems']) ? $item['subItems'][0]['skuId'] : $item['skuId'])->__toString()
                    );
                }
                $product->setPrice(
                    $this->getItemPrice($item)
                );
                // product add area
                $item = $quote->addProduct($product, $item['qty']);
            } catch (Throwable $e) {
                $message = __(
                    "Error adding product with SKU '%1' to quote, error: " . (string) $e->getMessage(),
                    isset($item['subItems']) ? $item['subItems'][0]['skuId'] : $item['skuId'],
                );
                $this->logger->error($message);
                throw new LocalizedException(
                    $message,
                    $e
                );
            }
        }
    }

    /**
     * validar o campo “subitem“, quando este existe deverá aplicar as formulas:
     * Para obter o valor do item unitário fazer: (order.item.salePrice / (item.subItem.qty * item.qty) ) * (item.subItem.percentageOfTotal / 100)
     * Quando não houver subitem o valor de cada item será:
     * item.salePrice / item.qty
     *
     * @param mixed $item
     * @return float
     */
    public function getItemPrice($item)
    {
        if (isset($item['subItems'])) {
            $price = $item['salePrice'] / ($item['subItems'][0]['qty'] * $item['qty']);
            if (isset($item['subItems'][0]['percentageOfTotal'])) {
                $price *= $item['subItems'][0]['percentageOfTotal'] / 100;
            }
        } else {
            print_r($item);
            exit;
            $price = $item['salePrice'] / $item['qty'];
        }
        return $price;
    }

    protected function getProductByGubeeSku(
        string $sku
    ): ProductInterface {
        if (strpos($sku, Variation::SEPARATOR) !== false) {
            $sku = explode(Variation::SEPARATOR, $sku);
            $sku = end($sku);
        }
        try {
            $product = $this->productRepository->get($sku);
            if (! $product->getId()) {
                throw new NoSuchEntityException(
                    __("Product with SKU '%1' not found on Magento", $sku)
                );
            }
        } catch (NoSuchEntityException $e) {
            throw new NoSuchEntityException(
                __("Product with SKU '%1' not found on Magento", $sku)
            );
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }

        return $product;
    }

    public function createAddress($address, $customer, $mageCustomerId)
    {
        $customer = new DataObject($customer);
        $region   = ObjectManager::getInstance()->create(
            Region::class
        )->loadByName(
            $address->getRegion(),
            'BR'
        );

        $country    = ObjectManager::getInstance()->create(Country::class)
            ->loadByCode(
                'BR'
            );
        $phone      = $customer->getData('phones/0');
        $phone      = sprintf(
            "(%s) %s",
            $phone['ddd'],
            $phone['number']
        );
        $name       = explode(' ', $customer->getName());
        $secondName = end($name);
        unset($name[sizeof($name) - 1]);
        $firstName = implode(' ', $name);
        $address   = [
            'firstname'            => $firstName,
            'lastname'             => $secondName,
            'email'                => $customer->getEmail(),
            'street'               => [
                $address->getStreet() ?: __("Street not informed"),
                $address->getNumber() ?: __("Number not informed"),
                $address->getComplement() ?: __("Complement not informed"),
            ],
            'city'                 => $address->getCity(),
            'country_id'           => $country->getId(),
            'region'               => $region->getDefaultName(),
            'region_id'            => $region->getId(),
            'postcode'             => $address->getData('postCode'),
            'telephone'            => $phone,
            'save_in_address_book' => 1,
            'customer_id'          => $mageCustomerId,
        ];
        return $address;
    }
}
