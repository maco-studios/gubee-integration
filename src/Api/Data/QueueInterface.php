<?php

declare(strict_types=1);

namespace Gubee\Integration\Api\Data;

interface QueueInterface
{
    public const STATUS_PENDING = 0;
    public const STATUS_RUNNING = 1;
    public const STATUS_STOPPED = 2;
    public const STATUS_FAILED = 3;
    public const STATUS_ERROR = 4;
    public const STATUS_SUCCESS = 5;

    public const UPDATED_AT = 'updated_at';
    public const QUEUE_ID = 'queue_id';
    public const HANDLER = 'handler';
    public const CREATED_AT = 'created_at';
    public const ERROR_MESSAGE = 'error_message';
    public const RESPONSE = 'response';
    public const PAYLOAD = 'payload';
    public const STATUS = 'status';
    public const ATTEMPTS = 'attempts';
    public const EXECUTED_AT = 'executed_at';

    /**
     * Get queue_id
     *
     * @return string|null
     */
    public function getQueueId();

    /**
     * Set queue_id
     *
     * @param string $queueId
     * @return \Gubee\Integration\Queue\Api\Data\QueueInterface
     */
    public function setQueueId($queueId);

    /**
     * Get process
     *
     * @return string|null
     */
    public function getHandler();

    /**
     * Set process
     *
     * @param string $process
     * @return \Gubee\Integration\Queue\Api\Data\QueueInterface
     */
    public function setHandler($process);

    /**
     * Get error
     *
     * @return string|null
     */
    public function getErrorMessage();

    /**
     * Set error
     *
     * @param string $errorMessage
     * @return \Gubee\Integration\Queue\Api\Data\QueueInterface
     */
    public function setErrorMessage($errorMessage);

    /**
     * Get response
     *
     * @return string|null
     */
    public function getResponse();

    /**
     * Set response
     *
     * @param string $response
     * @return \Gubee\Integration\Queue\Api\Data\QueueInterface
     */
    public function setResponse($response);

    /**
     * Get payload
     *
     * @return string|null
     */
    public function getPayload();

    /**
     * Set payload
     *
     * @param string $payload
     * @return \Gubee\Integration\Queue\Api\Data\QueueInterface
     */
    public function setPayload($payload);

    /**
     * Get status
     *
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     *
     * @param mixed $status
     * @return \Gubee\Integration\Queue\Api\Data\QueueInterface
     */
    public function setStatus($status);

    /**
     * Get executed_at
     *
     * @return string|null
     */
    public function getExecutedAt();

    /**
     * Set executed_at
     *
     * @param string $executedAt
     * @return \Gubee\Integration\Queue\Api\Data\QueueInterface
     */
    public function setExecutedAt($executedAt);

    /**
     * Get attempts
     *
     * @return int|null
     */
    public function getAttempts();

    /**
     * Set attempts
     *
     * @param int $attempts
     * @return \Gubee\Integration\Queue\Api\Data\QueueInterface
     */
    public function setAttempts($attempts);

    /**
     * Get created_at
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     *
     * @param string $createdAt
     * @return \Gubee\Integration\Queue\Api\Data\QueueInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated_at
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     *
     * @param string $updatedAt
     * @return \Gubee\Integration\Queue\Api\Data\QueueInterface
     */
    public function setUpdatedAt($updatedAt);
}
