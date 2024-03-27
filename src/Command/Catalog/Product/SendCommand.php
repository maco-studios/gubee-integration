<?php

declare(strict_types=1);

namespace Gubee\Integration\Command\Catalog\Product;

use Exception;
use Gubee\Integration\Command\AbstractCommand;
use Gubee\Integration\Service\Model\Catalog\Product;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;

use function __;
use function sprintf;

class SendCommand extends AbstractCommand
{
    protected ProductRepositoryInterface $productRepository;
    protected ObjectManagerInterface $objectManager;

    public function __construct(
        ManagerInterface $eventDispatcher,
        LoggerInterface $logger,
        ProductRepositoryInterface $productRepository,
        ObjectManagerInterface $objectManager
    ) {
        parent::__construct($eventDispatcher, $logger, "catalog:product:send");
        $this->productRepository = $productRepository;
        $this->objectManager     = $objectManager;
    }

    protected function configure()
    {
        $this->setDescription("Send the product to Gubee");
        $this->addArgument(
            'sku',
            InputArgument::REQUIRED,
            'The product SKU to be inserted'
        );
    }

    protected function doExecute(): int
    {
        $mageProduct = $this->productRepository->get($this->input->getArgument('sku'));
        if (! $mageProduct->getId()) {
            $this->log->error(
                sprintf(
                    "%s",
                    __(
                        "The product with the SKU '%1' does not exist",
                        $this->input->getArgument('sku')
                    )->__toString()
                )
            );
            return 1;
        }

        $product = $this->objectManager->create(
            Product::class,
            [
                'product' => $mageProduct,
            ]
        );
        try {
            $product->save();
            $mageProduct->setData(
                'gubee_integration_status',
                1
            );
            $this->productRepository->save($mageProduct);
        } catch (Exception $e) {
            $this->logger->error(
                sprintf(
                    "%s",
                    __(
                        "An error occurred while sending the product with the SKU '%1'",
                        $this->input->getArgument('sku')
                    )->__toString()
                )
            );
            return 1;
        }
        return 0;
    }

    public function getPriority(): int
    {
        return 800;
    }
}
