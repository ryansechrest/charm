<?php

namespace Charm\WordPress;

use WP_Post;
use WP_Term;

/**
 * Class NavMenuItem
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress
 */
class NavMenuItem extends Post
{
    /************************************************************************************/
    // Properties

    /**
     * Menu ID
     *
     * @var int
     */
    protected $menu_id = 0;

    /**
     * Database ID
     *
     * @var int
     */
    protected $db_id = 0;

    /**
     * Menu item parent
     *
     * @var int
     */
    protected $menu_item_parent = 0;

    /**
     * Object ID
     *
     * @var int
     */
    protected $object_id = 0;

    /**
     * Object
     *
     * @var string
     */
    protected $object = '';

    /**
     * Type
     *
     * @var string
     */
    protected $type = '';

    /**
     * Type label
     *
     * @var string
     */
    protected $type_label = '';

    /**
     * URL
     *
     * @var string
     */
    protected $url = '';

    /**
     * Title
     *
     * @var string
     */
    protected $title = '';

    /**
     * Target
     *
     * @var string
     */
    protected $target = '';

    /**
     * Attribute title
     *
     * @var string
     */
    protected $attr_title = '';

    /**
     * Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * Classes
     *
     * @var array
     */
    protected $classes = [];

    /**
     * XFN
     *
     * @var string
     */
    protected $xfn = '';

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        parent::load($data);
        if (isset($data['menu_id'])) {
            $this->menu_id = (int) $data['menu_id'];
        }
        if (isset($data['db_id'])) {
            $this->db_id = (int) $data['db_id'];
        }
        if (isset($data['menu_item_parent'])) {
            $this->menu_item_parent = (int) $data['menu_item_parent'];
        }
        if (isset($data['object_id'])) {
            $this->object_id = (int) $data['object_id'];
        }
        if (isset($data['object'])) {
            $this->object = $data['object'];
        }
        if (isset($data['type'])) {
            $this->type = $data['type'];
        }
        if (isset($data['type_label'])) {
            $this->type_label = $data['type_label'];
        }
        if (isset($data['url'])) {
            $this->url = $data['url'];
        }
        if (isset($data['title'])) {
            $this->title = $data['title'];
        }
        if (isset($data['target'])) {
            $this->target = $data['target'];
        }
        if (isset($data['attr_title'])) {
            $this->attr_title = $data['attr_title'];
        }
        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
        if (isset($data['classes'])) {
            $this->classes = $data['classes'];
        }
        if (isset($data['xfn'])) {
            $this->xfn = $data['xfn'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize post
     *
     * @see wp_setup_nav_menu_item()
     * @param int|string|WP_Post|null $key
     * @return static|null
     */
    public static function init($key = null): ?Post
    {
        $post = parent::init($key);
        if ($post === null) {
            return null;
        }
        $nav_menu_item = wp_setup_nav_menu_item($post->wp_post());
        $post->load_from_nav_menu_item($nav_menu_item);

        return $post;
    }

    /**
     * Get nav menu items
     *
     * @param array $params
     * @return array
     */
    public static function get(array $params = []): array
    {
        if (isset($params['menu'])) {
            return static::get_by_menu($params['menu'], $params);
        }
        if (isset($params['location'])) {
            return static::get_by_location($params['location'], $params);
        }

        return [];
    }

    /**
     * Get nav menu items by menu
     *
     * @see wp_get_nav_menu_items()
     * @param int|string|WP_Term $menu
     * @param array $params
     * @return static[]
     */
    public static function get_by_menu($menu, $params = []): array
    {
        $nav_menu = NavMenu::init($menu);
        if ($nav_menu === null) {
            return [];
        }
        $nav_menu_items = wp_get_nav_menu_items($nav_menu->get_term_id(), $params);
        if ($nav_menu_items === false) {
            return [];
        }

        return array_map(function($nav_menu_item) use ($nav_menu) {
            $item = static::init($nav_menu_item);
            $item->set_menu_id($nav_menu->get_term_id());
            return $item;
        }, $nav_menu_items);
    }

    /**
     * Get nav menu items by location
     *
     * @see get_nav_menu_locations()
     * @param string $location
     * @param array $params
     * @return static[]
     */
    public static function get_by_location(string $location, $params = []): array
    {
        $locations = get_nav_menu_locations();
        if (!isset($locations[$location])) {
            return [];
        }

        return static::get_by_menu($locations[$location], $params);
    }

    /************************************************************************************/
    // Protected load methods

    /**
     * Load instance from ID
     *
     * @see get_post()
     * @param int $id
     */
    protected function load_from_id(int $id): void
    {
        if (!$post = get_post($id)) {
            return;
        }
        $this->load_from_post($post);
    }

    /**
     * Load instance from path
     *
     * @see get_page_by_path()
     * @param string $path
     */
    protected function load_from_path(string $path): void
    {
        if (!$post = get_page_by_path($path, OBJECT, 'nav_menu_item')) {
            return;
        }
        $this->load_from_post($post);
    }

    /**
     * Load instance from nav menu item object
     *
     * @see WP_Post
     * @param WP_Post $nav_menu_item
     */
    protected function load_from_nav_menu_item(WP_Post $nav_menu_item): void
    {
        $this->load_from_post($nav_menu_item);
        // WordPress adds the following properties dynamically to WP_Post
        $this->db_id = $nav_menu_item->db_id;
        $this->menu_item_parent = $nav_menu_item->menu_item_parent;
        $this->object_id = $nav_menu_item->object_id;
        $this->object = $nav_menu_item->object;
        $this->type = $nav_menu_item->type;
        $this->type_label = $nav_menu_item->type_label;
        $this->url = $nav_menu_item->url;
        $this->title = $nav_menu_item->title;
        $this->target = $nav_menu_item->target;
        $this->attr_title = $nav_menu_item->attr_title;
        $this->description = $nav_menu_item->description;
        $this->classes = $nav_menu_item->classes;
        $this->xfn = $nav_menu_item->xfn;
    }

    /************************************************************************************/
    // Action methods

    /**
     * Create new nav menu item
     *
     * @see
     * @return bool
     */
    public function create(): bool
    {

    }

    /**
     * Update existing nav menu item
     *
     * @see wp_update_nav_menu_item()
     * @return bool
     */
    public function update(): bool
    {

    }

    /**
     * Delete nav menu item
     *
     * @see
     * @return bool
     */
    public function delete(): bool
    {

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
        $data = parent::to_array();
        if ($this->menu_id !== 0) {
            $data['menu_id'] = $this->menu_id;
        }
        if ($this->db_id !== 0) {
            $data['db_id'] = $this->db_id;
        }
        if ($this->menu_item_parent !== 0) {
            $data['menu_item_parent'] = $this->menu_item_parent;
        }
        if ($this->object_id !== 0) {
            $data['object_id'] = $this->object_id;
        }
        if ($this->object !== '') {
            $data['object'] = $this->object;
        }
        if ($this->type !== '') {
            $data['type'] = $this->type;
        }
        if ($this->type_label !== '') {
            $data['type_label'] = $this->type_label;
        }
        if ($this->url !== '') {
            $data['url'] = $this->url;
        }
        if ($this->title !== '') {
            $data['title'] = $this->title;
        }
        if ($this->target !== '') {
            $data['target'] = $this->target;
        }
        if ($this->attr_title !== '') {
            $data['attr_title'] = $this->attr_title;
        }
        if ($this->description !== '') {
            $data['description'] = $this->description;
        }
        if (count($this->classes) > 0) {
            $data['classes'] = $this->classes;
        }
        if ($this->xfn !== '') {
            $data['xfn'] = $this->xfn;
        }

        return $data;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get menu ID
     *
     * @return int
     */
    public function get_menu_id(): int
    {
        return $this->menu_id;
    }

    /**
     * Set menu ID
     *
     * @param int $menu_id
     */
    public function set_menu_id(int $menu_id): void
    {
        $this->menu_id = $menu_id;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get database ID
     *
     * @return int
     */
    public function get_db_id(): int
    {
        return $this->db_id;
    }

    /**
     * Set database ID
     *
     * @param int $db_id
     */
    public function set_db_id(int $db_id): void
    {
        $this->db_id = $db_id;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get menu item parent
     *
     * @return int
     */
    public function get_menu_item_parent(): int
    {
        return $this->menu_item_parent;
    }

    /**
     * Set menu item parent
     *
     * @param int $menu_item_parent
     */
    public function set_menu_item_parent(int $menu_item_parent): void
    {
        $this->menu_item_parent = $menu_item_parent;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get object ID
     *
     * @return int
     */
    public function get_object_id(): int
    {
        return $this->object_id;
    }

    /**
     * Set object ID
     *
     * @param int $object_id
     */
    public function set_object_id(int $object_id): void
    {
        $this->object_id = $object_id;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get object
     *
     * @return string
     */
    public function get_object(): string
    {
        return $this->object;
    }

    /**
     * Set object
     *
     * @param string $object
     */
    public function set_object(string $object): void
    {
        $this->object = $object;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get type
     *
     * @return string
     */
    public function get_type(): string
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function set_type(string $type): void
    {
        $this->type = $type;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get type label
     *
     * @return string
     */
    public function get_type_label(): string
    {
        return $this->type_label;
    }

    /**
     * Set type label
     *
     * @param string $type_label
     */
    public function set_type_label(string $type_label): void
    {
        $this->type_label = $type_label;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get URL
     *
     * @return string
     */
    public function get_url(): string
    {
        return $this->url;
    }

    /**
     * Set URL
     *
     * @param string $url
     */
    public function set_url(string $url): void
    {
        $this->url = $url;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get title
     *
     * @return string
     */
    public function get_title(): string
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function set_title(string $title): void
    {
        $this->title = $title;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get target
     *
     * @return string
     */
    public function get_target(): string
    {
        return $this->target;
    }

    /**
     * Set target
     *
     * @param string $target
     */
    public function set_target(string $target): void
    {
        $this->target = $target;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get attribute title
     *
     * @return string
     */
    public function get_attr_title(): string
    {
        return $this->attr_title;
    }

    /**
     * Set attribute title
     *
     * @param string $attr_title
     */
    public function set_attr_title(string $attr_title): void
    {
        $this->attr_title = $attr_title;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get description
     *
     * @return string
     */
    public function get_description(): string
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function set_description(string $description): void
    {
        $this->description = $description;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get classes
     *
     * @return array
     */
    public function get_classes(): array
    {
        return $this->classes;
    }

    /**
     * Set classes
     *
     * @param array $classes
     */
    public function set_classes(array $classes): void
    {
        $this->classes = $classes;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get XFN
     *
     * @return string
     */
    public function get_xfn(): string
    {
        return $this->xfn;
    }

    /**
     * Set XFN
     *
     * @param string $xfn
     */
    public function set_xfn(string $xfn): void
    {
        $this->xfn = $xfn;
    }
}