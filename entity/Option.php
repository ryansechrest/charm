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
}