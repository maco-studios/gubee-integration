<?php

declare(strict_types=1);

namespace Gubee\Integration\Api\Data;

interface LogInterface
{
    /**
     * @var int
     */
    public const EMERGENCY = 0;

    /**
     * @var int
     */
    public const ALERT = 1;

    /**
     * @var int
     */
    public const CRITICAL = 2;

    /**
     * @var int
     */
    public const ERROR = 3;

    /**
     * @var int
     */
    public const WARNING = 4;

    /**
     * @var int
     */
    public const NOTICE = 5;

    /**
     * @var int
     */
    public const INFO = 6;

    /**
     * @var int
     */
    public const DEBUG = 7;

    /**
     * @var string
     */
    public const CONTEXT = 'context';

    /**
     * @var string
     */
    public const MESSAGE = 'message';

    /**
     * @var string
     */
    public const LOG_ID = 'log_id';

    /**
     * @var string
     */
    public const LEVEL = 'level';

    /**
     * @var string
     */
    public const CREATED_AT = 'created_at';

    /**
     * Get log_id
     *
     * @return string|null
     */
    public function getLogId();

    /**
     * Set log_id
     *
     * @param string $logId
     * @return \Gubee\Integration\Log\Api\Data\LogInterface
     */
    public function setLogId($logId);

    /**
     * Get message
     *
     * @return string|null
     */
    public function getMessage();

    /**
     * Set message
     *
     * @param string $message
     * @return \Gubee\Integration\Log\Api\Data\LogInterface
     */
    public function setMessage($message);

    /**
     * Get level
     *
     * @return string|null
     */
    public function getLevel();

    /**
     * Set level
     *
     * @param string $level
     * @return \Gubee\Integration\Log\Api\Data\LogInterface
     */
    public function setLevel($level);

    /**
     * Get context
     *
     * @return string|null
     */
    public function getContext();

    /**
     * Set context
     *
     * @param string $context
     * @return \Gubee\Integration\Log\Api\Data\LogInterface
     */
    public function setContext($context);

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
     * @return \Gubee\Integration\Log\Api\Data\LogInterface
     */
    public function setCreatedAt($createdAt);
}
