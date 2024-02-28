<?php

declare(strict_types=1);

namespace Gubee\Integration\Command\Catalog\Product\Price;

use Gubee\Integration\Command\AbstractCommand;
use Gubee\Integration\Service\Model\Catalog\Product\Price;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;

class SendCommand extends AbstractCommand
{
    protected ProductRepositoryInterface $productRepository;
    protected ObjectManagerInterface $objectManager;

    /**
     * @param string|null $name The name of the command; passing null means it must be set in configure()
     * @throws LogicException When the command name is empty.
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ManagerInterface $eventDispatcher,
        ObjectManagerInterface $objectManager,
        LoggerInterface $logger,
        ?string $name = null
    ) {
        $this->productRepository = $productRepository;
        $this->objectManager     = $objectManager;
        parent::__construct(
            $eventDispatcher,
            $logger,
            "catalog:product:price:send"
        );
    }

    protected function configure(): void
    {
        $this->setDescription("Send product price to Gubee");
        $this->addArgument('sku', InputArgument::REQUIRED, 'The product to send');
    }

    /**
     * Executes the command.
     */
    protected function doExecute(): int
    {
        $product = $this->productRepository->get(
            $this->input->getArgument('sku')
        );

        $price = $this->objectManager->create(
            Price::class,
            [
                'product' => $product,
            ]
        );

        $price->save();

        return self::SUCCESS;
    }
}
