<?php

declare(strict_types=1);

namespace Gubee\Integration\Model;

use Exception;
use Gubee\Integration\Api\Data\LogInterface;
use Gubee\Integration\Helper\Config;
use Gubee\Integration\Model\LogFactory;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;

use function array_filter;
use function array_merge;
use function floor;
use function in_array;
use function json_encode;
use function log;
use function memory_get_peak_usage;
use function memory_get_usage;
use function pow;
use function round;
use function sprintf;

use const JSON_NUMERIC_CHECK;
use const JSON_PRETTY_PRINT;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;
use const PHP_EOL;
use const PHP_SAPI;

class Logger implements LoggerInterface
{
    protected LogFactory $logFactory;
    protected LoggerInterface $logger;

    public function __construct(
        LogFactory $logFactory,
        Config $config,
        LoggerInterface $logger
    ) {
        $this->logFactory = $logFactory;
        $this->logger     = $logger;
        $this->config     = $config;
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
        if (! in_array($level, $this->config->getLogLevel())) {
            return;
        }
        try {
            $context = array_merge(
                $context,
                [
                    'Memory Usage'      => $this->formatMemorySize(
                        memory_get_usage(true)
                    ),
                    'Memory Peak Usage' => $this->formatMemorySize(
                        memory_get_peak_usage(true)
                    ),
                    'IP'                => $_SERVER['REMOTE_ADDR'] ?? null,
                    'User Agent'        => $_SERVER['HTTP_USER_AGENT'] ?? '',
                    'CLI'               => PHP_SAPI === 'cli',
                    'uri'               => $_SERVER['REQUEST_URI'] ?? '',
                ]
            );

            $context = array_filter($context, function ($value) {
                return ! empty($value);
            });

            $log = $this->logFactory->create();
            echo $message . PHP_EOL;
            $log->setLevel($level)
                ->setMessage($message)
                ->setContext(
                    json_encode(
                        $context,
                        JSON_UNESCAPED_SLASHES
                        | JSON_UNESCAPED_UNICODE
                        | JSON_PRETTY_PRINT
                        | JSON_NUMERIC_CHECK
                    )
                )->save();
        } catch (Exception $e) {
            $this->logger->error(
                $e->getMessage()
            );
        }
    }

    /**
     * Convert memory size to human readable format
     */
    public function formatMemorySize(int $size): string
    {
        $unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        return sprintf(
            "%s %s",
            @round($size / pow(1024, $i = floor(log($size, 1024))), 2),
            $unit[$i]
        );
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
