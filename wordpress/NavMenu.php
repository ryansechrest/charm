<?php

namespace Charm\WordPress;

use WP_Term;

/**
 * Class NavMenu
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress
 */
class NavMenu extends Term
{
    /************************************************************************************/
    // Constants

    /**
     * NavMenuItem class
     *
     * @var string
     */
    const ITEM = 'Charm\Entity\NavMenuItem';

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize nav menu
     *
     * @see wp_get_nav_menu_object()
     * @see get_nav_menu_locations()
     * @param int|string|WP_Term $key
     * @param string $taxonomy
     * @return static|null
     */
    public static function init($key, $taxonomy = 'nav_menu'): ?Term
    {
        $nav_menu = wp_get_nav_menu_object($key);
        if ($nav_menu !== false) {
            return parent::init($nav_menu, $taxonomy);
        }
        $menu_locations = get_nav_menu_locations();
        if (isset($menu_locations[$key])) {
            return parent::init($menu_locations[$key], $taxonomy);
        }

        return null;
    }

    /**
     * Get nav menus
     *
     * @see wp_get_nav_menus()
     * @param array $params
     * @return static[]
     */
    public static function get(array $params = []): array
    {
        return array_map(function(WP_Term $nav_menu) {
            return static::init($nav_menu);
        }, wp_get_nav_menus($params));
    }

    /************************************************************************************/
    // Action methods

    /**
     * Create nav menu
     *
     * @see wp_create_nav_menu()
     * @return bool
     */
    public function create(): bool
    {
        $term_id = wp_create_nav_menu($this->name);
        if (!is_int($term_id)) {
            return false;
        }
        $this->term_id = $term_id;
        $this->reload();

        return true;
    }

    /**
     * Update nav menu
     *
     * @see wp_update_nav_menu_object()
     * @return bool
     */
    public function update(): bool
    {
        $term_id = wp_update_nav_menu_object($this->term_id, [
            'menu-name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'parent' => $this->parent,
        ]);
        if (!is_int($term_id)) {
            return false;
        }
        $this->term_id = $term_id;
        $this->reload();

        return true;
    }

    /**
     * Delete nav menu
     *
     * @see wp_delete_nav_menu()
     * @return bool
     */
    public function delete(): bool
    {
        if (wp_delete_nav_menu($this->term_id) !== true) {
            return false;
        }

        return true;
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get nav menu items
     *
     * @param array $params
     * @return NavMenuItem[]
     */
    public function get_items($params = []): array
    {
        $params['menu_id'] = $this->term_id;

        return call_user_func(
            static::ITEM . '::get', $params
        );
    }
}