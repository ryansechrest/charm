<?php

namespace Charm\Admin;

use Charm\Feature\ParentSlug;
use Charm\Helper\Convert;
use Charm\WordPress\Admin\SubmenuPage as WpSubmenuPage;

/**
 * Class MenuPage
 *
 * @author Ryan Sechrest
 * @package Charm\Admin
 */
class SubmenuPage extends WpSubmenuPage
{
    use ParentSlug;

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
        if (isset($data['parent_menu'])) {
            $this->load_parent_slug($data['parent_menu']);
        }
    }

    /************************************************************************************/
    // Protected load methods

    /**
     * Load parent slug
     *
     * @param string $name
     */
    protected function load_parent_slug(string $name): void
    {
        $location = $this->get_location();
        if (!isset($this->parent_slugs[$location])) {
            return;
        }
        $name = strtolower($name);
        if (!isset($this->parent_slugs[$location][$name])) {
            return;
        }
        $this->parent_slug = $this->parent_slugs[$location][$name];
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