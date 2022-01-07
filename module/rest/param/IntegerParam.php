<?php

namespace Charm\Module\Rest\Param;

/**
 * Class IntegerParam
 *
 * @author Ryan Sechrest
 * @package Charm\Module\Rest\Param
 */
class IntegerParam extends Param
{
    /************************************************************************************/
    // Properties

    /**
     * Minimum
     *
     * @var int
     */
    protected int $minimum = 0;

    /**
     * Maximum
     *
     * @var int
     */
    protected int $maximum = 0;

    /**
     * Exclusive minimum
     *
     * @var bool
     */
    protected bool $exclusive_minimum = true;

    /**
     * Exclusive maximum
     *
     * @var bool
     */
    protected bool $exclusive_maximum = true;

    /**
     * Multiple of
     *
     * @var float
     */
    protected float $multiple_of = 0.0;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        parent::load($data);
        $this->type = 'integer';
        if (isset($data['minimum'])) {
            $this->minimum = $data['minimum'];
        }
        if (isset($data['maximum'])) {
            $this->maximum = $data['maximum'];
        }
        if (isset($data['exclusive_minimum'])) {
            $this->exclusive_minimum = $data['exclusive_minimum'];
        }
        if (isset($data['exclusive_maximum'])) {
            $this->exclusive_maximum = $data['exclusive_maximum'];
        }
        if (isset($data['multiple_of'])) {
            $this->multiple_of = $data['multiple_of'];
        }
    }

    /************************************************************************************/
    // Cast methods

    /**
     * Cast properties to array
     *
     * @return array
     */
    public function to_array(): array
    {
        $data = parent::to_array();
        if ($this->minimum !== 0) {
            $data['minimum'] = $this->minimum;
        }
        if ($this->maximum !== 0) {
            $data['maximum'] = $this->maximum;
        }
        if ($this->exclusive_minimum !== null) {
            $data['exclusive_minimum'] = $this->exclusive_minimum;
        }
        if ($this->exclusive_maximum !== null) {
            $data['exclusive_maximum'] = $this->exclusive_maximum;
        }
        if ($this->multiple_of !== 0.0) {
            $data['multiple_of'] = $this->multiple_of;
        }

        return $data;
    }

    /**
     * Cast properties to array for WordPress
     *
     * @return array
     */
    public function to_array_for_wp(): array
    {
        $data = parent::to_array();
        if ($this->minimum !== 0) {
            $data['minimum'] = $this->minimum;
        }
        if ($this->maximum !== 0) {
            $data['maximum'] = $this->maximum;
        }
        if ($this->exclusive_minimum !== null) {
            $data['exclusiveMinimum'] = $this->exclusive_minimum;
        }
        if ($this->exclusive_maximum !== null) {
            $data['exclusiveMaximum'] = $this->exclusive_maximum;
        }
        if ($this->multiple_of !== 0.0) {
            $data['multipleOf'] = $this->multiple_of;
        }

        return $data;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get minimum
     *
     * @return int
     */
    public function get_minimum(): int
    {
        return $this->minimum;
    }

    /**
     * Set minimum
     *
     * @param int $minimum
     */
    public function set_minimum(int $minimum): void
    {
        $this->minimum = $minimum;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get maximum
     *
     * @return int
     */
    public function get_maximum(): int
    {
        return $this->maximum;
    }

    /**
     * Set maximum
     *
     * @param int $maximum
     */
    public function set_maximum(int $maximum): void
    {
        $this->maximum = $maximum;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is exclusive minimum?
     *
     * @return bool
     */
    public function is_exclusive_minimum(): bool
    {
        return $this->exclusive_minimum;
    }

    /**
     * Set exclusive minimum
     *
     * @param bool $exclusive_minimum
     */
    public function set_exclusive_minimum(bool $exclusive_minimum): void
    {
        $this->exclusive_minimum = $exclusive_minimum;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is exclusive maximum?
     *
     * @return bool
     */
    public function is_exclusive_maximum(): bool
    {
        return $this->exclusive_maximum;
    }

    /**
     * Set exclusive maximum
     *
     * @param bool $exclusive_maximum
     */
    public function set_exclusive_maximum(bool $exclusive_maximum): void
    {
        $this->exclusive_maximum = $exclusive_maximum;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get multiple of
     *
     * @return float
     */
    public function get_multiple_of(): float
    {
        return $this->multiple_of;
    }

    /**
     * Set multiple of
     *
     * @param float $multiple_of
     */
    public function set_multiple_of(float $multiple_of): void
    {
        $this->multiple_of = $multiple_of;
    }
}