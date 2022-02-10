<?php

namespace Charm\Admin;

use Charm\Feature\MenuPosition;
use Charm\Helper\Convert;
use Charm\WordPress\Admin\MenuPage as WpMenuPage;

/**
 * Class MenuPage
 *
 * @author Ryan Sechrest
 * @package Charm\Admin
 */
class MenuPage extends WpMenuPage
{
    use MenuPosition;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['title'])) {
            $this->page_title = $data['title'];
            $this->menu_title = $data['title'];
            $this->menu_slug = $this->autogenerate_menu_slug();
        }
        if (isset($data['network'])) {
            $this->hook_prefix = 'network';
        }
        parent::load($data);
        // Relies on hook_prefix to be loaded
        if (isset($data['position_after'])) {
            $this->load_position($data['position_after']);
        }
    }

    /************************************************************************************/
    // Protected load methods

    /**
     * Load position
     *
     * @param array|string $data
     */
    protected function load_position(array|string $data): void
    {
        $location = $this->get_location();
        if (!isset($this->menu_positions[$location])) {
            return;
        }
        $name = is_array($data) ? $data[0] : $data;
        $name = strtolower($name);
        $place = is_array($data) ? $data[1] : 1;
        if (!isset($this->menu_positions[$location][$name])) {
            return;
        }
        if (!is_array($this->menu_positions[$location][$name])) {
            $this->position = $this->menu_positions[$location][$name];
            return;
        }
        if (!isset($this->menu_positions[$location][$name][$place])) {
            return;
        }
        $this->position = $this->menu_positions[$location][$name][$place];
    }

    /************************************************************************************/
    // Autogenerate methods

    /**
     * Autogenerate menu slug based on page title
     *
     * @return string
     */
    public function autogenerate_menu_slug(): string
    {
        return Convert::init($this->page_title)->t2s()->value();
    }
}