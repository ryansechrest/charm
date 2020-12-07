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

    /*----------------------------------------------------------------------------------*/

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
        $params['menu_id'] = $this->term_id;

        return call_user_func(
            static::ITEM . '::get', $params
        );
    }
}