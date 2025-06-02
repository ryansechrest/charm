<?php

namespace Charm\Support;

/**
 * Filter a value based on specified arguments.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Filter
{
    /**
     * Value to be filtered.
     *
     * @var array
     */
    protected mixed $value = null;

    // *************************************************************************

    /**
     * Initialize `Filter` from a value.
     *
     * @param mixed $value
     */
    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    // *************************************************************************

    /**
     * Create a new `Filter` instance from an array.
     *
     * @param array $value
     * @return self
     */
    public static function array(array $value): self
    {
        return new self($value);
    }

    // *************************************************************************

    /**
     * Filter the array to only include values with specified keys.
     *
     * @param array $keys
     * @return self
     */
    public function only(array $keys): self
    {
        $this->value = array_intersect_key($this->value, array_flip($keys));

        return $this;
    }

    /**
     * Filter the array to exclude values with specified keys.
     *
     * @param array $keys
     * @return self
     */
    public function except(array $keys): self
    {
        $this->value = array_diff_key($this->value, array_flip($keys));

        return $this;
    }

    /**
     * Filter the array to exclude keys with null values.
     *
     * @return self
     */
    public function withoutNulls(): self
    {
        $this->value = array_filter(
            $this->value, fn($value) => !is_null($value)
        );

        return $this;
    }

    // *************************************************************************

    /**
     * Get filtered value.
     *
     * @return mixed
     */
    public function get(): mixed
    {
        return $this->value;
    }
}