<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\Queue;

use Gubee\Integration\Api\Data\MessageInterface;
use Gubee\Integration\Api\Data\MessageInterfaceFactory;
use Gubee\Integration\Api\Enum\Message\StatusEnum;
use Gubee\Integration\Api\MessageRepositoryInterface;
use Gubee\Integration\Model\ResourceModel\Message\CollectionFactory as MessageCollectionFactory;
use Psr\Log\LoggerInterface;
use Throwable;

use function json_encode;

class Management
{
    protected LoggerInterface $logger;
    protected MessageCollectionFactory $messageCollectionFactory; /* @phpstan-ignore-line */
    protected MessageInterfaceFactory $messageFactory; /* @phpstan-ignore-line */
    protected MessageRepositoryInterface $messageRepository;

    public function __construct(
        LoggerInterface $logger,
        MessageCollectionFactory $messageCollectionFactory, /* @phpstan-ignore-line */
        MessageInterfaceFactory $messageFactory, /* @phpstan-ignore-line */
        MessageRepositoryInterface $messageRepository
    ) {
        $this->logger                   = $logger;
        $this->messageCollectionFactory = $messageCollectionFactory;
        $this->messageFactory           = $messageFactory;
        $this->messageRepository        = $messageRepository;
    }

    /**
     * Append a job to the queue with the given command and parameters.
     *
     * @param array<int|string, mixed> $params
     */
    public function append(string $command, array $params = []): self
    {
        try {
            /* @phpstan-ignore-next-line */
            $message = $this->messageFactory->create();
            $message->setCommand($command);
            $message->setPayload($params);

            if (! $this->alreadyQueued($message)) {
                $this->messageRepository->save($message);
            }
        } catch (Throwable $th) {
            $this->logger->error(
                "Queue Management: Error while appending message to queue",
                [
                    'exception' => $th,
                    'command'   => $command,
                    'params'    => $params,
                ]
            );
        }

        return $this;
    }

    /**
     * Check if given message is already queued
     */
    public function alreadyQueued(MessageInterface $message): bool
    {
        /* @phpstan-ignore-next-line */
        $queued = $this->messageCollectionFactory->create()
            ->addFieldToFilter(MessageInterface::COMMAND, $message->getCommand());
        if ($message->getPayload()) {
            $queued->addFieldToFilter(MessageInterface::PAYLOAD, json_encode($message->getPayload()));
        }

        return $queued->addFieldToFilter(MessageInterface::STATUS, StatusEnum::PENDING())
            ->getSize() > 0;
    }
}
