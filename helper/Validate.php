<?php

namespace Charm\Helper;

/**
 * Class Validate
 *
 * @author Ryan Sechrest
 * @package Charm\Helper
 */
class Validate
{
    /************************************************************************************/
    // Properties

    /**
     * Value
     *
     * @var mixed
     */
    protected mixed $value = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Validate constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (count($data) === 0) {
            return;
        }
        $this->load($data);
    }

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['value'])) {
            $this->value = $data['value'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize validate
     *
     * @param mixed $value
     * @return self
     */
    public static function init(mixed $value): self
    {
        return new static([
            'value' => $value,
        ]);
    }

    /************************************************************************************/
    // Chainable validation methods



    /**
     * Return value in current state
     *
     * @return mixed
     */
    public function value(): mixed
    {
        return $this->value;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get value
     *
     * @return mixed
     */
    public function get_value(): mixed
    {
        return $this->value;
    }

    /**
     * Set value
     *
     * @param mixed $value
     */
    public function set_value(mixed $value)
    {
        $this->value = $value;
    }
}