<?php

namespace Charm\Skeleton;

use Charm\Module\MenuLocation as MenuLocationModule;

/**
 * Class Menu
 *
 * @author Ryan Sechrest
 * @package Charm\Skeleton
 */
abstract class MenuLocation
{
    /************************************************************************************/
    // Action methods

    /**
     * Register menu location
     */
    public static function register(): void
    {
        static::menu_location()->register();
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get menu location
     *
     * @return MenuLocationModule|null
     */
    public static function menu_location(): ?MenuLocationModule
    {
        return null;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get name
     *
     * @return string
     */
    public static function name(): string
    {
        return static::menu_location()->get_location();
    }
}