<?php

namespace Charm\Helper;

/**
 * Class Cast
 *
 * @author Ryan Sechrest
 * @package Charm\Helper
 */
class Cast
{
    /************************************************************************************/
    // Properties

    /**
     * Value
     *
     * @var mixed
     */
    protected $value = null;

    /**
     * Original value
     *
     * @var mixed
     */
    protected $original_value = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Post constructor
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
        if (isset($data['original_value'])) {
            $this->original_value = $data['original_value'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize converter
     *
     * @param mixed $value
     * @return self
     */
    public static function init($value): self
    {
       return new static([
           'value' => $value,
           'original_value' => $value,
       ]);
    }

    /************************************************************************************/
    // Cast methods

    /**
     * Cast value to array
     *
     * @see maybe_unserialize()
     * @return array
     */
    public function array(): array
    {
        $array = maybe_unserialize($this->value);
        if ($array === null) {
            return $this->value = [];
        }
        if (!is_array($array)) {
            return $this->value = [$array];
        }

        return $this->value = $array;
    }

    /**
     * Cast value to bool
     *
     * @return bool
     */
    public function bool(): bool
    {
        if (is_bool($this->value)) {
            return $this->value;
        }
        if (is_string($this->value) && $this->value === 'true') {
            return $this->value = true;
        }
        if (is_numeric($this->value) && $this->value === 1) {
            return $this->value = true;
        }

        return $this->value = false;
    }

    /**
     * Cast value to integer
     *
     * @return int
     */
    public function int(): int
    {
        if (!is_numeric($this->value)) {
            return $this->value = 0;
        }

        return $this->value = (int) $this->value;
    }

    /**
     * Cast value to string
     *
     * @return string
     */
    public function string(): string
    {
        if (!$string = (string) $this->value) {
            return $this->value = '';
        }

        return $this->value = $string;
    }

    /**
     * Return value in current state
     *
     * @return mixed
     */
    public function value()
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
    public function get_value()
    {
        return $this->value;
    }

    /**
     * Set value
     *
     * @param mixed $value
     */
    public function set_value($value)
    {
        $this->value = $value;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get original value
     *
     * @return mixed
     */
    public function get_original_value()
    {
        return $this->original_value;
    }

    /**
     * Set original value
     *
     * @param mixed $value
     */
    public function set_original_value($value)
    {
        $this->original_value = $value;
    }
}