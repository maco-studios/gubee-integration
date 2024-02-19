<?php

declare(strict_types=1);

namespace Gubee\Integration\Model;

use Gubee\Integration\Api\Data\QueueInterface;
use Magento\Framework\Model\AbstractModel;

class Queue extends AbstractModel implements QueueInterface
{
    /**
     * @inheritDoc
     */
    //phpcs:disable
    public function _construct()
    {
        $this->_init(\Gubee\Integration\Model\ResourceModel\Queue::class);
    }
    //phpcs:enable

    /**
     * @inheritDoc
     */
    public function getQueueId()
    {
        return $this->getData(self::QUEUE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setQueueId($queueId)
    {
        return $this->setData(self::QUEUE_ID, $queueId);
    }

    /**
     * @inheritDoc
     */
    public function getProcess()
    {
        return $this->getData(self::PROCESS);
    }

    /**
     * @inheritDoc
     */
    public function setProcess($process)
    {
        return $this->setData(self::PROCESS, $process);
    }

    /**
     * @inheritDoc
     */
    public function getPayload()
    {
        return $this->getData(self::PAYLOAD);
    }

    /**
     * @inheritDoc
     */
    public function setPayload($payload)
    {
        return $this->setData(self::PAYLOAD, $payload);
    }

    /**
     * @inheritDoc
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getExecutedAt()
    {
        return $this->getData(self::EXECUTED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setExecutedAt($executedAt)
    {
        return $this->setData(self::EXECUTED_AT, $executedAt);
    }

    /**
     * @inheritDoc
     */
    public function getAttempts()
    {
        return $this->getData(self::ATTEMPTS);
    }

    /**
     * @inheritDoc
     */
    public function setAttempts($attempts)
    {
        return $this->setData(self::ATTEMPTS, $attempts);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
