<?php

declare(strict_types=1);

namespace Gubee\Integration\Api\Enum;

use InvalidArgumentException;
use JsonSerializable;
use ReflectionClass;
use Stringable;

use function __;
use function implode;
use function in_array;

abstract class AbstractEnum implements Stringable, JsonSerializable
{
    protected string $value;

    /**
     * Constructs a new AbstractEnum instance with the specified value.
     *
     * @param string $value The value of the enumeration.
     */
    protected function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    /**
     * Returns the string representation of the enumeration.
     *
     * @return string The string representation of the enumeration.
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Validates the given value against the allowed values of the enumeration.
     *
     * @param string $value The value to validate.
     * @throws InvalidArgumentException If the value is not allowed.
     */
    protected function validate(string $value): void
    {
        $consts = (new ReflectionClass(static::class))->getConstants();
        if (! in_array($value, $consts, true)) {
            throw new InvalidArgumentException(
                (string) __(
                    'Invalid value for %1: %2. Allowed values are: %3',
                    static::class,
                    $value,
                    implode(', ', $consts)
                )
            );
        }
    }

    /**
     * Serializes the enumeration to a JSON string.
     *
     * @return string The JSON representation of the enumeration.
     */
    public function jsonSerialize(): string
    {
        return $this->value;
    }

    /**
     * Creates an instance of the enum class based on the given value.
     *
     * @param string $value The value to create the enum instance from.
     * @return self The enum instance.
     */
    abstract public static function fromValue(string $value): self;
}
