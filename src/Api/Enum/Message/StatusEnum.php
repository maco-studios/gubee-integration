<?php

declare(strict_types=1);

namespace Gubee\Integration\Api\Enum\Message;

use Gubee\Integration\Api\Enum\AbstractEnum;

class StatusEnum extends AbstractEnum
{
    private const PENDING  = "0";
    private const RUNNING  = "1";
    private const ERROR    = "3";
    private const FINISHED = "2";
    private const FAILED   = "4";

    public static function PENDING(): self
    {
        return new self(self::PENDING);
    }

    public static function RUNNING(): self
    {
        return new self(self::RUNNING);
    }

    public static function ERROR(): self
    {
        return new self(self::ERROR);
    }

    public static function FINISHED(): self
    {
        return new self(self::FINISHED);
    }

    public static function FAILED(): self
    {
        return new self(self::FAILED);
    }

    /**
     * Creates an instance of the enum class based on the given value.
     * w
     *
     * @param string $value The value to create the enum instance from.
     * @return self The enum instance.
     */
    public static function fromValue(string $value): self
    {
        return new self($value);
    }
}