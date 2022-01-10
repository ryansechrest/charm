<?php

namespace Charm\Entity;

use Charm\WordPress\NavMenu as WpNavMenu;
use Charm\WordPress\Term;
use ReflectionClass;
use ReflectionException;
use WP_Term;

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
    // Properties

    /**
     * Nav menu items
     *
     * @var NavMenuItem[]
     */
    protected array $items = [];

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize nav menu
     *
     * @param WP_Term|int|string|null $key
     * @param string $taxonomy
     * @return static|null
     */
    public static function init(WP_Term|int|string $key = null, string $taxonomy = 'nav_menu'): ?Term
    {
        if ($key === null && static::menu_location() !== '') {
            return parent::init(static::menu_location());
        }

        return parent::init($key, $taxonomy);
    }

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
     * Get nav menu items
     *
     * @return NavMenuItem[] array
     */
    public static function items(): array
    {
        if (!$menu = static::init()) {
            return [];
        }

        return $menu->get_items();
    }

    /**
     * Get menu location
     *
     * @return string
     */
    public static function menu_location(): string
    {
        return '';
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Create new nav menu item
     *
     * @param array $params
     * @return bool
     */
    public function new_item(array $params = []): bool
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
    public function get_items(array $params = []): array
    {
        if (count($this->items) > 0) {
            return $this->items;
        }
        $params['menu_id'] = $this->term_id;

        return $this->items = $this->build_tree(call_user_func(
            static::ITEM . '::get', $params
        ));
    }

    /**
     * Build nav menu item tree
     *
     * @param NavMenuItem[] $items
     * @param int $parent_id
     * @return array
     */
    private function build_tree(array $items, int $parent_id = 0): array
    {
        $branch = [];
        foreach ($items as $item) {
            if ($item->get_menu_item_parent() == $parent_id) {
                $sub_items = $this->build_tree($items, $item->get_db_id());
                if (count($sub_items) > 0) {
                    $item->set_sub_items($sub_items);
                }
                $branch[] = $item;
            }
        }

        return $branch;
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