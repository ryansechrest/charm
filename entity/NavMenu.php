<?php

namespace Charm\Entity;

/**
 * Class NavMenu
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class NavMenu extends Term
{
    /************************************************************************************/
    // Object access methods

    /**
     * Get taxonomy
     *
     * @return string
     */
    public static function taxonomy(): string
    {
        return 'nav_menu';
    }
}