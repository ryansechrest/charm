<?php

namespace Charm\Entity;

use Charm\WordPress\NavMenu as WpNavMenu;

/**
 * Class NavMenu
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class NavMenu extends WpNavMenu
{
    /************************************************************************************/
    // Object access methods

    /**
     * Get nav menu ID
     *
     * @return int
     */
    public function id(): int
    {
        return $this->term_id;
    }
}