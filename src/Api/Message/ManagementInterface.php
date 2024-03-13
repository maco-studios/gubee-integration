<?php

declare(strict_types=1);

namespace Gubee\Integration\Api\Message;

use Gubee\Integration\Api\Data\MessageInterface;
use Magento\Framework\Api\SearchResults;

interface ManagementInterface
{
    /**
     * Process given queue message
     */
    public function process(MessageInterface $message): void;

    /**
     * Process a bunch of messages at once
     *
     * @param iterable<MessageInterface> $message
     */
    public function massProcess(iterable $message): void;

    /**
     * Get messages waiting to be processed
     */
    public function getPending(): SearchResults;

    /**
     * Get failed messages waiting to be retried
     *
     * @return array<MessageInterface>
     */
    public function getToBeRetried(): array;
}
