<?php

namespace Charm\Entity;

use Charm\WordPress\NavMenuItem as WpNavMenuItem;

/**
 * Class NavMenuItem
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class NavMenuItem extends WpNavMenuItem
{
    /************************************************************************************/
    // Properties

    /**
     * Menu item parent object
     *
     * @var NavMenuItem|null
     */
    protected $menu_item_parent_obj = null;

    /************************************************************************************/
    // Object access methods

    /**
     * Get menu item parent
     *
     * @return NavMenuItem|null
     */
    public function menu_item_parent(): ?NavMenuItem
    {
        if ($this->menu_item_parent_obj) {
            return $this->menu_item_parent_obj;
        }
        if (!$this->menu_item_parent) {
            return null;
        }

        return $this->menu_item_parent_obj = static::init($this->menu_item_parent);
    }
}