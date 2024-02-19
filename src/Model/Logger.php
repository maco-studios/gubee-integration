<?php

declare(strict_types=1);

namespace Gubee\Integration\Model;

use Exception;
use Gubee\Integration\Api\Data\LogInterface;
use Gubee\Integration\Model\LogFactory;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;

use function json_encode;

class Logger implements LoggerInterface
{
    protected LogFactory $logFactory;
    protected LoggerInterface $logger;

    public function __construct(
        LogFactory $logFactory,
        LoggerInterface $logger
    ) {
        $this->logFactory = $logFactory;
        $this->logger     = $logger;
    }

    /**
     * System is unusable.
     *
     * @param string  $message
     * @param mixed[] $context
     * @return void
     */
    public function emergency($message, array $context = [])
    {
        $this->log(
            LogInterface::EMERGENCY,
            $message,
            $context
        );
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string  $message
     * @param mixed[] $context
     * @return void
     */
    public function alert($message, array $context = [])
    {
        $this->log(
            LogInterface::ALERT,
            $message,
            $context
        );
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string  $message
     * @param mixed[] $context
     * @return void
     */
    public function critical($message, array $context = [])
    {
        $this->log(
            LogInterface::CRITICAL,
            $message,
            $context
        );
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string  $message
     * @param mixed[] $context
     * @return void
     */
    public function error($message, array $context = [])
    {
        $this->log(
            LogInterface::ERROR,
            $message,
            $context
        );
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string  $message
     * @param mixed[] $context
     * @return void
     */
    public function warning($message, array $context = [])
    {
        $this->log(
            LogInterface::WARNING,
            $message,
            $context
        );
    }

    /**
     * Normal but significant events.
     *
     * @param string  $message
     * @param mixed[] $context
     * @return void
     */
    public function notice($message, array $context = [])
    {
        $this->log(
            LogInterface::NOTICE,
            $message,
            $context
        );
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string  $message
     * @param mixed[] $context
     * @return void
     */
    public function info($message, array $context = [])
    {
        $this->log(
            LogInterface::INFO,
            $message,
            $context
        );
    }

    /**
     * Detailed debug information.
     *
     * @param string  $message
     * @param mixed[] $context
     * @return void
     */
    public function debug($message, array $context = [])
    {
        $this->log(
            LogInterface::DEBUG,
            $message,
            $context
        );
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed   $level
     * @param string  $message
     * @param mixed[] $context
     * @return void
     * @throws InvalidArgumentException
     */
    public function log($level, $message, array $context = [])
    {
        try {
            $log = $this->logFactory->create();
            $log->setLevel($level)
                ->setMessage($message)
                ->setContext(
                    json_encode($context)
                )->save();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    public function getLogFactory(): LogFactory
    {
        return $this->logFactory;
    }

    public function setLogFactory(LogFactory $logFactory): self
    {
        $this->logFactory = $logFactory;
        return $this;
    }
}
