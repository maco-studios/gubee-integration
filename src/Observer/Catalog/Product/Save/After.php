<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer\Catalog\Product\Save;

use Gubee\Integration\Command\Catalog\Product\SendCommand;
use Gubee\Integration\Helper\Catalog\Attribute;
use Gubee\Integration\Model\Config;
use Gubee\Integration\Model\Queue\Management;
use Gubee\Integration\Observer\Catalog\Product\AbstractProduct;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;

class After extends AbstractProduct
{
    protected Registry $registry;

    public function __construct(
        Config $config,
        LoggerInterface $logger,
        Management $queueManagement,
        Attribute $attribute,
        ProductRepositoryInterface $productRepository,
        ObjectManagerInterface $objectManager,
        Registry $registry
    ) {
        parent::__construct(
            $config,
            $logger,
            $queueManagement,
            $attribute,
            $productRepository,
            $objectManager
        );
        $this->registry = $registry;
    }

    protected function process(): void
    {
        if ($message = $this->registry->registry('gubee_current_message')) {
            if ($message->getCommand() == SendCommand::class) {
                return;
            }
        }

        $this->queueManagement->append(
            SendCommand::class,
            [
                'sku' => $this->getProduct()->getSku(),
            ],
            (int) $this->getProduct()->getId()
        );
    }
}
