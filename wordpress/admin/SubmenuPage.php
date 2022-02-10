<?php

namespace Charm\WordPress\Admin;

/**
 * Class SubmenuPage
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Admin
 */
class SubmenuPage
{
    /************************************************************************************/
    // Properties

    /**
     * Parent slug (Required)
     *
     * The slug name for the parent menu (or the file name of a standard WordPress
     * admin page).
     *
     * Individual admin:
     *
     *  Dashboard -> index.php
     *  Posts -> edit.php
     *  Media -> upload.php
     *  Pages -> edit.php?post_type=page
     *  Comments -> edit-comments.php
     *  Appearance -> themes.php
     *  Plugins -> plugins.php
     *  Users -> users.php
     *  Tools -> tools.php
     *  Settings -> options-general.php
     *
     * Network admin:
     *
     *  Dashboard -> index.php
     *  Sites -> sites.php
     *  Users -> users.php
     *  Themes -> themes.php
     *  Plugins -> plugins.php
     *  Settings -> settings.php
     *
     * @var string
     */
    protected string $parent_slug = '';

    /**
     * Page title (Required)
     *
     * The text to be displayed in the title tags of the page when the menu is selected.
     *
     * @var string
     */
    protected string $page_title = '';

    /**
     * Menu title (Required)
     *
     * The text to be used for the menu.
     *
     * @var string
     */
    protected string $menu_title = '';

    /**
     * Capability (Required)
     *
     * The capability required for this menu to be displayed to the user.
     *
     * @var string
     */
    protected string $capability = '';

    /**
     * Menu slug (Required)
     *
     * The slug name to refer to this menu by. Should be unique for this menu page and
     * only include lowercase alphanumeric, dashes, and underscores characters to be
     * compatible with sanitize_key().
     *
     * @var string
     */
    protected string $menu_slug = '';

    /**
     * Function
     *
     * 	The function to be called to output the content for this page. Default value: ''
     *
     * @var callable
     */
    protected $function = null;

    /**
     * Position
     *
     * The position in the menu order this item should appear. Default value: null
     *
     * @var int|null
     */
    protected ?int $position = null;

    /*----------------------------------------------------------------------------------*/

    /**
     * Hook prefix
     *
     * Options:
     *
     *  <blank> -> Add to individual site admin
     *  user -> Add to individual user admin
     *  network -> Add to multi-site network admin
     *
     * @var string
     */
    protected string $hook_prefix = '';

    /**
     * Page hook
     *
     * Property to store result of add_submenu_page().
     *
     * @var string
     */
    protected string $page_hook = '';

    /************************************************************************************/
    // Default constructor and load method

    /**
     * SubmenuPage constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (count($data) === 0) {
            return;
        }
        $this->load($data);
    }

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['hook_prefix'])) {
            $this->hook_prefix = $data['hook_prefix'];
        }
        if (isset($data['parent_slug'])) {
            $this->parent_slug = $data['parent_slug'];
        } else {
            $this->default_parent_slug();
        }
        if (isset($data['page_title'])) {
            $this->page_title = $data['page_title'];
        }
        if (isset($data['menu_title'])) {
            $this->menu_title = $data['menu_title'];
        }
        if (isset($data['capability'])) {
            $this->capability = $data['capability'];
        } else {
            $this->default_capability();
        }
        if (isset($data['menu_slug'])) {
            $this->menu_slug = $data['menu_slug'];
        }
        if (isset($data['function'])) {
            $this->function = $data['function'];
        }
        if (isset($data['position'])) {
            $this->position = $data['position'];
        }
    }

    /************************************************************************************/
    // Default methods

    /**
     * Default parent slug
     */
    public function default_parent_slug(): void
    {
        $default_parent_slug = 'options-general.php';
        if ($this->get_location() === 'network') {
            $default_parent_slug = 'settings.php';
        }
        $this->parent_slug = $default_parent_slug;
    }

    /**
     * Default capability
     */
    public function default_capability(): void
    {
        $default_capability = 'manage_options';
        if ($this->get_location() === 'network') {
            $default_capability = 'manage_network_options';
        }
        $this->capability = $default_capability;
    }

    /************************************************************************************/
    // Action methods

    /**
     * Register submenu page
     *
     * @see add_submenu_page()
     */
    public function register(): void
    {
        add_action($this->get_admin_menu_hook(), function() {
            $page_hook = add_submenu_page(
                $this->parent_slug,
                $this->page_title,
                $this->menu_title,
                $this->capability,
                $this->menu_slug,
                $this->function,
                $this->position
            );
            if ($page_hook === false) {
                return;
            }
            $this->page_hook = $page_hook;
        });
    }

    /**
     * Unregister submenu page
     *
     * @see remove_submenu_page()
     */
    public function unregister(): void
    {
        add_action($this->get_admin_menu_hook(), function() {
            remove_submenu_page($this->parent_slug, $this->menu_slug);
        });
    }

    /************************************************************************************/
    // Cast methods

    /**
     * Cast properties to array
     *
     * @return array
     */
    public function to_array(): array
    {
        $data = [];
        if ($this->parent_slug !== '') {
            $data['parent_slug'] = $this->parent_slug;
        }
        if ($this->page_title !== '') {
            $data['page_title'] = $this->page_title;
        }
        if ($this->menu_title !== '') {
            $data['menu_title'] = $this->menu_title;
        }
        if ($this->capability !== '') {
            $data['capability'] = $this->capability;
        }
        if ($this->menu_slug !== '') {
            $data['menu_slug'] = $this->menu_slug;
        }
        if ($this->function !== null) {
            $data['function'] = $this->function;
        }
        if ($this->position !== null) {
            $data['position'] = $this->position;
        }
        if ($this->hook_prefix !== '') {
            $data['hook_prefix'] = $this->hook_prefix;
        }
        if ($this->page_hook !== '') {
            $data['page_hook'] = $this->page_hook;
        }

        return $data;
    }

    /**
     * Cast instance to JSON
     *
     * @return string
     */
    public function to_json(): string
    {
        return json_encode($this->to_array());
    }
    /**
     * Cast instance to object
     *
     * @return object
     */
    public function to_object(): object
    {
        return (object) $this->to_array();
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get admin menu hook
     *  e.g. admin_menu, user_admin_menu, network_admin_menu
     *
     * @return string
     */
    protected function get_admin_menu_hook(): string
    {
        $admin_menu_hook = 'admin_menu';
        if ($this->hook_prefix) {
            $admin_menu_hook = $this->hook_prefix . '_' . $admin_menu_hook;
        }

        return $admin_menu_hook;
    }

    /**
     * Get location
     *  e.g. site or network
     *
     * @return string
     */
    protected function get_location(): string
    {
        return $this->hook_prefix === '' ? 'site' : 'network';
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get parent slug
     *
     * @return string
     */
    public function get_parent_slug(): string
    {
        return $this->parent_slug;
    }

    /**
     * Set parent slug
     *
     * @param string $parent_slug
     */
    public function set_parent_slug(string $parent_slug): void
    {
        $this->parent_slug = $parent_slug;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get page title
     *
     * @return string
     */
    public function get_page_title(): string
    {
        return $this->page_title;
    }

    /**
     * Set page title
     *
     * @param string $page_title
     */
    public function set_page_title(string $page_title): void
    {
        $this->page_title = $page_title;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get menu title
     *
     * @return string
     */
    public function get_menu_title(): string
    {
        return $this->menu_title;
    }

    /**
     * Set menu title
     *
     * @param string $menu_title
     */
    public function set_menu_title(string $menu_title): void
    {
        $this->menu_title = $menu_title;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get capability
     *
     * @return string
     */
    public function get_capability(): string
    {
        return $this->capability;
    }

    /**
     * Set capability
     *
     * @param string $capability
     */
    public function set_capability(string $capability): void
    {
        $this->capability = $capability;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get menu slug
     *
     * @return string
     */
    public function get_menu_slug(): string
    {
        return $this->menu_slug;
    }

    /**
     * Set menu slug
     *
     * @param string $menu_slug
     */
    public function set_menu_slug(string $menu_slug): void
    {
        $this->menu_slug = $menu_slug;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get function
     *
     * @return callable
     */
    public function get_function(): callable
    {
        return $this->function;
    }

    /**
     * Set function
     *
     * @param callable $function
     */
    public function set_function(callable $function): void
    {
        $this->function = $function;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get position
     *
     * @return int
     */
    public function get_position(): int
    {
        return $this->position;
    }

    /**
     * Set position
     *
     * @param int $position
     */
    public function set_position(int $position): void
    {
        $this->position = $position;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get hook prefix
     *
     * @return string
     */
    public function get_hook_prefix(): string
    {
        return $this->hook_prefix;
    }

    /**
     * Set hook prefix
     *
     * @param string $hook_prefix
     */
    public function set_hook_prefix(string $hook_prefix): void
    {
        $this->hook_prefix = $hook_prefix;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get page hook
     *
     * @return string
     */
    public function get_page_hook(): string
    {
        return $this->page_hook;
    }

    /**
     * Set page hook
     *
     * @param string $page_hook
     */
    public function set_page_hook(string $page_hook): void
    {
        $this->page_hook = $page_hook;
    }
}