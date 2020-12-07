<?php

namespace Charm\Entity;

use Charm\WordPress\NavMenu as WpNavMenu;
use ReflectionClass;
use ReflectionException;

/**
 * Class NavMenu
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class NavMenu extends WpNavMenu
{
    /**
     * Nav menu items
     *
     * @var NavMenuItem[]
     */
    protected $items = [];

    /************************************************************************************/
    // Constants

    /**
     * NavMenuItem class
     *
     * @var string
     */
    const ITEM = 'Charm\Entity\NavMenuItem';

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

    /************************************************************************************/
    // Get and set methods

    /**
     * Create new nav menu item
     *
     * @param array $params
     * @return bool
     */
    public function new_item($params = []): bool
    {
        $params['menu_id'] = $this->term_id;

        try {
            $reflection = new ReflectionClass(static::ITEM);
            $nav_menu_item = $reflection->newInstanceArgs([$params]);
            $this->items = [];
            return $nav_menu_item->create();
        } catch (ReflectionException $e) {
            return false;
        }
    }

    /**
     * Get nav menu items
     *
     * @param array $params
     * @return NavMenuItem[]
     */
    public function get_items($params = []): array
    {
        if (count($this->items) > 0) {
            return $this->items;
        }
        $params['menu_id'] = $this->term_id;

        return $this->items = call_user_func(
            static::ITEM . '::get', $params
        );
    }

    /**
     * Set nav menu items
     *
     * @param array $items
     */
    public function set_items(array $items): void
    {
        $this->items = $items;
    }
}