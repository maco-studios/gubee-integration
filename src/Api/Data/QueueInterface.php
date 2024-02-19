<?php

declare(strict_types=1);

namespace Gubee\Integration\Api\Data;

interface QueueInterface
{
    const UPDATED_AT  = 'updated_at';
    const QUEUE_ID    = 'queue_id';
    const PROCESS     = 'process';
    const CREATED_AT  = 'created_at';
    const PAYLOAD     = 'payload';
    const STATUS      = 'status';
    const ATTEMPTS    = 'attempts';
    const EXECUTED_AT = 'executed_at';

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
    public function getProcess();

    /**
     * Set process
     *
     * @param string $process
     * @return \Gubee\Integration\Queue\Api\Data\QueueInterface
     */
    public function setProcess($process);

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
     * @param string $status
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
     * @return string|null
     */
    public function getAttempts();

    /**
     * Set attempts
     *
     * @param string $attempts
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
