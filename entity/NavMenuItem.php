<?php

namespace Charm\Entity;

use Charm\WordPress\NavMenuItem as WpNavMenuItem;
use WP_Query;

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
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        $data['post_type'] = static::post_type();
        parent::load($data);
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Get nav menu items
     *
     * @param array $params
     * @return static[]
     */
    public static function get(array $params = []): array
    {
        return self::query($params)->posts;
    }

    /**
     * Query using WP_Query
     *
     * @param array $params
     * @return WP_Query
     */
    public static function query(array $params = []): WP_Query
    {
        $params['post_type'] = static::post_type();

        return parent::query($params);
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get post type
     *
     * @return string
     */
    public static function post_type(): string
    {
        return 'nav_menu_item';
    }

    /*----------------------------------------------------------------------------------*/

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