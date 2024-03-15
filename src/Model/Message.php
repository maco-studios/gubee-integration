<?php

declare(strict_types=1);

namespace Gubee\Integration\Model;

use DateTimeInterface;
use Gubee\Integration\Api\Data\MessageInterface;
use Gubee\Integration\Api\Enum\Message\StatusEnum;
use Gubee\Integration\Command\AbstractCommand;
use InvalidArgumentException;
use Magento\Framework\Model\AbstractModel;

use function __;
use function is_subclass_of;
use function json_decode;
use function json_encode;
use function json_last_error;

use const JSON_ERROR_NONE;

class Message extends AbstractModel implements MessageInterface
{
    /**
     * @inheritDoc
     */
    // phpcs:disable
    public function _construct()
    {
        $this->_init(\Gubee\Integration\Model\ResourceModel\Message::class);
    }
    // phpcs:enable

    public function beforeSave(): self
    {
        if ($this->getData(self::STATUS) instanceof StatusEnum) {
            $this->setData(
                self::STATUS,
                (int) $this->getData(self::STATUS)->__toString()
            );
        }

        if ($this->getData(self::PAYLOAD) !== null) {
            parent::setData(
                self::PAYLOAD,
                ! $this->isJson(
                    $this->getData(self::PAYLOAD)
                ) ? json_encode(
                    $this->getData(self::PAYLOAD)
                ) : $this->getData(self::PAYLOAD)
            );
        }
        return parent::beforeSave();
    }

    protected function isJson(string $content): bool
    {
        json_decode($content);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Get the message ID.
     *
     * @return int The message ID.
     */
    public function getMessageId(): int
    {
        return (int) $this->getData(self::MESSAGE_ID);
    }

    /**
     * Set the message ID.
     *
     * @param int $messageId The message ID.
     */
    public function setMessageId(int $messageId): self
    {
        return $this->setData(self::MESSAGE_ID, $messageId);
    }

    /**
     * Get the command.
     *
     * @return string The command.
     */
    public function getCommand(): string
    {
        return (string) $this->getData(self::COMMAND);
    }

    /**
     * Set the command.
     *
     * @param string $command The command.
     */
    public function setCommand(string $command): self
    {
        if (! is_subclass_of($command, AbstractCommand::class)) {
            throw new InvalidArgumentException(
                __(
                    "The command must be an instance of '%1'.",
                    AbstractCommand::class
                )->__toString()
            );
        }

        return $this->setData(self::COMMAND, $command);
    }

    /**
     * Get the status.
     *
     * @return string The status.
     */
    public function getStatus(): string
    {
        return (string) $this->getData(self::STATUS);
    }

    /**
     * Set the status.
     *
     * @param StatusEnum $status The status.
     */
    public function setStatus(StatusEnum $status): self
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get the payload.
     *
     * @return array<int|string, mixed> The payload.
     */
    public function getPayload(): array
    {
        return $this->isJson($this->getData(self::PAYLOAD)) ? json_decode(
            $this->getData(self::PAYLOAD),
            true
        ) : $this->getData(self::PAYLOAD);
    }

    /**
     * Set the payload.
     *
     * @param array<int|string, mixed> $payload The payload.
     */
    public function setPayload(array $payload): self
    {
        if ($payload === []) {
            $payload = null;
        }
        return $this->setData(self::PAYLOAD, $payload);
    }

    /**
     * Get the number of attempts.
     *
     * @return int The number of attempts.
     */
    public function getAttempts(): int
    {
        return (int) $this->getData(self::ATTEMPTS);
    }

    /**
     * Set the number of attempts.
     *
     * @param int $attempts The number of attempts.
     */
    public function setAttempts(int $attempts): self
    {
        return $this->setData(self::ATTEMPTS, $attempts);
    }

    /**
     * Get the message.
     *
     * @return string The message.
     */
    public function getMessage(): string
    {
        return (string) $this->getData(self::MESSAGE);
    }

    /**
     * Set the message.
     *
     * @param string $message The message.
     */
    public function setMessage(string $message): self
    {
        return $this->setData(self::MESSAGE, $message);
    }

    /**
     * Get the creation date and time.
     *
     * @return DateTimeInterface The creation date and time.
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set the creation date and time.
     *
     * @param string $createdAt The creation date and time.
     */
    public function setCreatedAt(string $createdAt): self
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get the last update date and time.
     *
     * @return DateTimeInterface The last update date and time.
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Set the last update date and time.
     *
     * @param string $updatedAt The last update date and time.
     */
    public function setUpdatedAt(string $updatedAt): self
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
