<?php

declare(strict_types=1);

namespace Gubee\Integration\Command\Catalog\Product\Stock;

use Gubee\Integration\Service\Model\Catalog\Product\Stock;
use Symfony\Component\Console\Input\InputArgument;

class DesativateCommand extends SendCommand
{
    protected function configure(): void
    {
        $this->setName("catalog:product:stock:desativate");
        $this->setDescription("Desativate product stock to Gubee");
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

        $stock = $this->objectManager->create(
            Stock::class,
            [
                'product' => $product,
            ]
        );

        $stock->desativate();

        return self::SUCCESS;
    }
}
