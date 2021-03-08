<?php

namespace Charm\Entity;

use Charm\Helper\Caster;
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
     * Pass value to caster
     *
     * @return Caster
     */
    public function cast(): Caster
    {
        return Caster::init($this->value);
    }
}