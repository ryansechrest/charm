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
    // Instantiation methods

    /**
     * Initialize nav menu
     *
     * @see wp_get_nav_menu_object()
     * @see get_nav_menu_locations()
     * @param WP_Term|int|string $key
     * @param string $taxonomy
     * @return static|null
     */
    public static function init(WP_Term|int|string $key, string $taxonomy = 'nav_menu'): ?Term
    {
        $nav_menu = wp_get_nav_menu_object($key);
        if ($nav_menu !== false) {
            return parent::init($nav_menu, $taxonomy);
        }
        $menu_locations = get_nav_menu_locations();
        if (isset($menu_locations[$key]) && $menu_locations[$key] !== 0) {
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
}