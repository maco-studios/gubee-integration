<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer\Gubee\Command\Sales\Order\Processor\Created\Run;

use Gubee\Integration\Command\Sales\Order\AbstractProcessorCommand;
use Gubee\Integration\Command\Sales\Order\Processor\Exception\BlacklistedException;
use Gubee\Integration\Model\Config;
use Gubee\Integration\Model\Message;
use Gubee\Integration\Model\Queue\Management;
use Gubee\Integration\Observer\AbstractObserver;
use Gubee\SDK\Resource\PlatformResource;
use Gubee\SDK\Resource\Sales\OrderResource;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;
use ReflectionClass;

use function in_array;
use function is_array;
use function is_subclass_of;
use function sprintf;
use function str_replace;
use function strtoupper;

class Before extends AbstractObserver
{
    protected Registry $registry;
    protected PlatformResource $platformResource;
    protected OrderResource $orderResource;

    public function __construct(
        Config $config,
        LoggerInterface $logger,
        Management $queueManagement,
        Registry $registry,
        PlatformResource $platformResource,
        OrderResource $orderResource
    ) {
        parent::__construct($config, $logger, $queueManagement);
        $this->registry         = $registry;
        $this->platformResource = $platformResource;
        $this->orderResource    = $orderResource;
    }

    protected function process(): void
    {
        /** @var Message $message */
        $message = $this->registry->registry('gubee_current_message');
        if (! is_subclass_of($message->getCommand(), AbstractProcessorCommand::class)) {
            return;
        }

        $command    = $message->getCommand();
        $class      = new ReflectionClass($command);
        $className  = $class->getShortName();
        $blacklist  = $this->getBlacklist();
        $statusName = str_replace(
            "Command",
            "",
            $className
        );
        $statusName = strtoupper($statusName);

        $order = $this->getOrder($message->getPayload()['order_id']);
        if (! $order) {
            $this->logger->error(
                sprintf(
                    "Order with ID %s not found",
                    $message->getPayload()['order_id']
                )
            );
            return;
        }

        if (
            isset($blacklist[$order['plataform']])
            &&
            in_array(
                $statusName,
                is_array($blacklist[$order['plataform']]) ? $blacklist[$order['plataform']] : [$blacklist[$order['plataform']]]
            )
        ) {
            throw new BlacklistedException(
                sprintf(
                    "The message with status '%s' is blacklisted for platform '%s' and order ID '%s' is from this platform",
                    $statusName,
                    $order['plataform'],
                    $order['id']
                )
            );
        }
    }

    public function getBlacklist()
    {
        $response  = $this->platformResource->createdBlacklist();
        $blacklist = [];
        foreach ($response as $item) {
            $blacklist[$item['name']] = $item['status'];
        }
        return $blacklist;
    }

    public function getOrder(string $orderId)
    {
        return $this->orderResource->loadByOrderId($orderId);
    }
}
