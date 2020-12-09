<?php

namespace Charm\Entity;

use Charm\WordPress\NavMenuItem as WpNavMenuItem;
use WP_Post;
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
     * Sub nav menu items
     *
     * @var NavMenuItem[]
     */
    protected $sub_items = [];

    /**
     * Menu item parent object
     *
     * @var NavMenuItem|null
     */
    protected $menu_item_parent_obj = null;

    /************************************************************************************/
    // Object access methods

    /**
     * Get nav menu ID
     *
     * @return int
     */
    public function id(): int
    {
        return $this->db_id;
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

    /************************************************************************************/
    // Cast methods

    /**
     * Cast instance to array
     *
     * @return array
     */
    public function to_array(): array
    {
        return array_merge(
            parent::to_array(),
            ['sub_items' => array_map(function($item) {
                return $item->to_array();
            }, $this->get_sub_items())]
        );
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get sub nav menu items
     *
     * @see WP_Query
     * @return NavMenuItem[]
     */
    public function get_sub_items(): array
    {
        if (count($this->sub_items) > 0) {
            return $this->sub_items;
        }
        $query = new WP_Query([
            'post_type' => 'nav_menu_item',
            'order' => 'ASC',
            'orderby' => 'menu_order',
            'meta_key' => '_menu_item_menu_item_parent',
            'meta_value' => $this->db_id,
            'nopaging' => true,
        ]);
        if (!$query->found_posts) {
            return [];
        }

        return $this->sub_items = array_map(function(WP_Post $post) {
            return static::init($post);
        }, $query->posts);
    }

    /**
     * Set sub items
     *
     * @param NavMenuItem[] $sub_items
     */
    public function set_sub_items(array $sub_items): void
    {
        $this->sub_items = $sub_items;
    }
}