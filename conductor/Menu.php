<?php

namespace Charm\Entity;

use Charm\Module\MenuLocation;

/**
 * Class Menu
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class Menu
{
    /************************************************************************************/
    // Constants

    /**
     * Term class
     *
     * @var string
     */
    const TERM = 'Charm\Entity\NavMenu';

    /**
     * Post class
     *
     * @var string
     */
    const POST = 'Charm\Entity\NavMenuItem';

    /************************************************************************************/
    // Properties

    /**
     * Menu locations
     *
     * @var array
     */
    protected $menu_locations = [];

    /*----------------------------------------------------------------------------------*/

    /**
     * Term object
     *
     * @var Term
     */
    protected $term_obj = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['menu_locations'])) {
            $this->menu_locations = $data['menu_locations'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize menu
     *
     * @param int|string|Term $key
     * @return static|null
     */
    public static function init($key): ?Menu
    {
        $nav_menu = new static();
        if (is_int($key) || ctype_digit($key)) {
            $nav_menu->load_from_term_id($key);
        } elseif (is_object($key) && is_a($key, NavMenu::class)) {
            $nav_menu->load_from_term($key);
        } elseif (is_string($key) && class_exists($key)) {
            $nav_menu->load_from_menu_location_class($key);
        } elseif (is_string($key)) {
            $nav_menu->load_from_menu_location($key);
        }
        if ($nav_menu->term_obj === null) {
            return null;
        }

        return $nav_menu;
    }

    /************************************************************************************/
    // Protected load methods

    /**
     * Load instance from term ID
     *
     * @param int $term_id
     */
    protected function load_from_term_id(int $term_id): void
    {
        $term = call_user_func(
            static::TERM . '::init', $term_id
        );
        if ($term === null) {
            return;
        }
        $this->load_from_term($term);
    }

    /**
     * Load instance from term
     *
     * @param NavMenu $term
     */
    protected function load_from_term(NavMenu $term): void
    {
        if ($term->get_taxonomy() !== 'nav_menu') {
            return;
        }
        $this->term_obj = $term;
        $this->load_locations_from_term();
    }

    /**
     * Load instance from menu location class
     *
     * @param string $menu_location_class
     */
    protected function load_from_menu_location_class(string $menu_location_class): void
    {
        $menu_location = call_user_func($menu_location_class . '::menu_location');
        if (!is_a($menu_location, MenuLocation::class)) {
            return;
        }
        $this->load_from_menu_location($menu_location->get_location());
    }

    /**
     * Load instance from menu location
     *
     * @param string $menu_location
     */
    protected function load_from_menu_location(string $menu_location): void
    {
        $this->menu_locations[] = $menu_location;
        $this->load_term_from_location();
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Load locations from term
     *
     * @see get_nav_menu_locations()
     */
    protected function load_locations_from_term()
    {
        if ($this->term_obj === null) {
            return;
        }
        if ($this->term_obj->id() === 0) {
            return;
        }
        $nav_menu_locations = get_nav_menu_locations();
        foreach ($nav_menu_locations as $location => $term_id) {
            if ($this->term_obj->id() !== $term_id) {
                continue;
            }
            if (in_array($location, $this->menu_locations)) {
                continue;
            }
            $this->menu_locations[] = $location;
        }
    }

    /**
     * Load term from location
     *
     * @see get_nav_menu_locations()
     */
    protected function load_term_from_location()
    {
        $nav_menu_locations = get_nav_menu_locations();
        if (count($this->menu_locations) !== 1) {
            return;
        }
        if (!isset($nav_menu_locations[$this->menu_locations[0]])) {
            return;
        }
        $this->load_from_term_id($nav_menu_locations[$this->menu_locations[0]]);
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get term
     *
     * @return Term|null
     */
    public function term(): ?Term
    {
        return $this->term_obj;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get nav menu items
     *
     * @return NavMenuItem[]
     */
    public function get_items(): array
    {
        return call_user_func(static::POST . '::get', [
            'posts_per_page' => '-1',
            'tax_query' => [
                [
                    'taxonomy' => 'nav_menu',
                    'field' => 'term_id',
                    'terms' => $this->term_obj->get_term_id(),
                ]
            ]
        ]);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get menu locations
     *
     * @return array
     */
    public function get_menu_locations()
    {
        return $this->menu_locations;
    }
}