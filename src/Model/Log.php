<?php

declare(strict_types=1);

namespace Gubee\Integration\Model;

use Gubee\Integration\Api\Data\LogInterface;
use Magento\Framework\Model\AbstractModel;

class Log extends AbstractModel implements LogInterface
{
    /**
     * @inheritDoc
     */
    //phpcs:disable
    public function _construct()
    {
        $this->_init(\Gubee\Integration\Model\ResourceModel\Log::class);
    }
    //phpcs:enable

    /**
     * @inheritDoc
     */
    public function getLogId()
    {
        return $this->getData(self::LOG_ID);
    }

    /**
     * @inheritDoc
     */
    public function setLogId($logId)
    {
        return $this->setData(self::LOG_ID, $logId);
    }

    /**
     * @inheritDoc
     */
    public function getMessage()
    {
        return $this->getData(self::MESSAGE);
    }

    /**
     * @inheritDoc
     */
    public function setMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }

    /**
     * @inheritDoc
     */
    public function getLevel()
    {
        return $this->getData(self::LEVEL);
    }

    /**
     * @inheritDoc
     */
    public function setLevel($level)
    {
        return $this->setData(self::LEVEL, $level);
    }

    /**
     * @inheritDoc
     */
    public function getContext()
    {
        return $this->getData(self::CONTEXT);
    }

    /**
     * @inheritDoc
     */
    public function setContext($context)
    {
        return $this->setData(self::CONTEXT, $context);
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
}
