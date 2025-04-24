<?php

namespace Charm\Support;

/**
 * Cast a value to something else.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Cast
{
    /**
     * Value
     *
     * @var mixed
     */
    protected mixed $value = null;

    /**************************************************************************/

    /**
     * Cast constructor
     *
     * @param mixed $value
     */
    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    /**************************************************************************/

    /**
     * Initialize cast from value
     *
     * @param mixed $value
     * @return static
     */
    public static function from(mixed $value): self
    {
        return new static($value);
    }

    /**************************************************************************/

    /**
     * Cast value to array
     *
     * @return array
     */
    public function toArray(): array
    {
        if (is_array($this->value)) {
            return $this->value;
        }

        if (is_object($this->value)) {
            return (array) $this->value;
        }

        if (is_string($this->value)) {
            $json = json_decode($this->value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
                return $json;
            }
        }

        return [$this->value];
    }

    /**
     * Cast value to bool
     *
     * @return bool
     */
    public function toBool(): bool
    {
        $value = $this->value;

        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            $value = strtolower(trim($value));
            return in_array($value, ['1', 'true', 'yes', 'on'], true);
        }

        return (bool) $value;
    }

    /**
     * Cast value to float
     *
     * @return float
     */
    public function toFloat(): float
    {
        return is_numeric($this->value) ? (float) $this->value : 0.0;
    }

    /**
     * Cast value to integer
     *
     * @return int
     */
    public function toInt(): int
    {
        if (!is_numeric($this->value)) {
            return 0;
        }

        return (int) $this->value;
    }

    /**
     * Cast value to JSON
     *
     * @return string
     */
    public function json(): string
    {
        return json_encode($this->value, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Cast value to string
     *
     * @return string
     */
    public function toString(): string
    {
        return (string) $this->value;
    }
}