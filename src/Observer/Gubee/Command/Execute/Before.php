<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer\Gubee\Command\Execute;

use Gubee\Integration\Model\Config;
use Gubee\Integration\Model\Queue\Management;
use Gubee\Integration\Observer\AbstractObserver;
use Gubee\SDK\Resource\Sales\OrderResource;
use Magento\Framework\ObjectManager\ObjectManager;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;

use function __;
use function is_subclass_of;

class Before extends AbstractObserver
{
    protected Registry $registry;
    protected OrderResource $orderResource;

    public function __construct(
        Config $config,
        LoggerInterface $logger,
        Management $queueManagement,
        Registry $registry
    ) {
        parent::__construct($config, $logger, $queueManagement);
        $this->registry = $registry;
    }

    protected function process(): void
    {
        $rules = $this->config->getFulfilmentGridRules();
        if (empty($rules) || $rules == []) {
            $this->logger->debug(
                __("No rules found for the fulfilment grid. Skipping...")
            );
            return;
        }
        $message = $this->registry->registry('gubee_current_message');
        if (! is_subclass_of($message->getCommand(), AbstractProcessorCommand::class)) {
            return;
        }
        $orderResource = ObjectManager::getInstance()
            ->get(OrderResource::class);
        $order         = $orderResource->loadByOrderId(
            $message->getPayload()['order_id']
        );
    }
}
