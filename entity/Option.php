<?php

namespace Charm\Entity;

use Charm\Helper\Cast;
use Charm\WordPress\Option as WpOption;

/**
 * Class Option
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class Option extends WpOption
{
    /************************************************************************************/
    // Helper methods

    /**
     * Pass value to cast
     *
     * @return Cast
     */
    public function cast(): Cast
    {
        return Cast::init($this->value);
    }

    /************************************************************************************/
    // Check methods

    /**
     * Has value changed?
     *
     * @return bool
     */
    public function changed(): bool
    {
        if ($this->value !== $this->prev_value) {
            return true;
        }

        return false;
    }

    /**
     * Does value exist?
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->value !== null;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is string blank?
     *
     * @return bool
     */
    public function is_blank(): bool
    {
        return $this->exists() && $this->value === '';
    }

    /**
     * Is array empty?
     *
     *
     * @return bool
     */
    public function is_empty(): bool
    {
        return $this->exists() && count($this->value) === 0;
    }

    /**
     * Is integer zero?
     *
     * @return bool
     */
    public function is_zero(): bool
    {
        return $this->exists() && $this->value === 0;
    }
}