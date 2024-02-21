<?php

declare(strict_types=1);

namespace Gubee\Integration\Command\Catalog\Product\Attribute;

use Exception;
use Gubee\Integration\Command\AbstractCommand;
use Gubee\Integration\Service\Model\Catalog\Product\Attribute;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputArgument;

class SendCommand extends AbstractCommand
{
    protected ProductAttributeRepositoryInterface $productAttributeRepository;
    protected Attribute $attribute;
    protected ObjectManagerInterface $objectManager;

    /**
     * @param string|null $name The name of the command; passing null means it must be set in configure()
     * @throws LogicException When the command name is empty.
     */
    public function __construct(
        ProductAttributeRepositoryInterface $productAttributeRepository,
        ObjectManagerInterface $objectManager,
        ManagerInterface $eventDispatcher,
        LoggerInterface $logger,
        ?string $name = null
    ) {
        $this->productAttributeRepository = $productAttributeRepository;
        $this->objectManager              = $objectManager;
        parent::__construct(
            $eventDispatcher,
            $logger,
            "catalog:product:attribute:send"
        );
    }

    /**
     * Configures the current command.
     */
    protected function configure(): void
    {
        $this->setDescription('Send a product attribute to the Gubee API')
            ->addArgument(
                'attribute_code',
                InputArgument::REQUIRED,
                'The attribute code'
            );
    }

    /**
     * Executes the command.
     */
    protected function doExecute(): int
    {
        $eavAttribute    = $this->productAttributeRepository->get(
            $this->input->getArgument('attribute_code')
        );
        $this->attribute = $this->objectManager->create(
            Attribute::class,
            [
                'eavAttribute' => $eavAttribute,
            ]
        );

        try {
            $this->attribute->save();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
