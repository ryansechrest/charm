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
     * Site menu positions:
     *
     *  2 – Dashboard
     *  4 – Separator
     *  5 – Posts
     *  10 – Media
     *  15 – Links
     *  20 – Pages
     *  25 – Comments
     *  59 – Separator
     *  60 – Appearance
     *  65 – Plugins
     *  70 – Users
     *  75 – Tools
     *  80 – Settings
     *  99 – Separator
     *
     * Network menu positions:
     *
     *  2 – Dashboard
     *  4 – Separator
     *  5 – Sites
     *  10 – Users
     *  15 – Themes
     *  20 – Plugins
     *  25 – Settings
     *  30 – Updates
     *  99 – Separator
     *
     * @var int|null
     */
    protected ?int $position = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Setting constructor
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
        if (isset($data['parent_slug'])) {
            $this->parent_slug = $data['parent_slug'];
        }
        if (isset($data['page_title'])) {
            $this->page_title = $data['page_title'];
        }
        if (isset($data['menu_title'])) {
            $this->menu_title = $data['menu_title'];
        }
        if (isset($data['capability'])) {
            $this->capability = $data['capability'];
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
    // Action methods

    /**
     * Register submenu page
     *
     * @see add_submenu_page()
     */
    public function register(): void
    {
        add_action('admin_menu', function() {
            add_submenu_page(
                $this->parent_slug,
                $this->page_title,
                $this->menu_title,
                $this->capability,
                $this->menu_slug,
                $this->function,
                $this->position
            );
        });
    }

    /**
     * Unregister submenu page
     *
     * @see remove_submenu_page()
     */
    public function unregister(): void
    {
        add_action('admin_menu', function() {
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
}