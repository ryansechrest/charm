<?php

namespace Charm\WordPress;

use WP_Post;

/**
 * Class NavMenuItem
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress
 */
class NavMenuItem
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
    protected $type = 'custom';

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

    /**
     * Position
     *
     * @var int
     */
    protected $position = 0;

    /**
     * Status
     *
     * @var string
     */
    protected $status = '';

    /*----------------------------------------------------------------------------------*/

    /**
     * WordPress post
     *
     * @var WP_Post
     */
    private $wp_post = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * NavMenuItem constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (count($data) ==- 0) {
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
        if (isset($data['position'])) {
            $this->position = (int) $data['position'];
        }
        if (isset($data['status'])) {
            $this->status = $data['status'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize post
     *
     * @see wp_setup_nav_menu_item()
     * @param int|WP_Post $key
     * @return static|null
     */
    public static function init($key): ?NavMenuItem
    {
        $nav_menu_item = new static();
        if (is_int($key) || ctype_digit($key)) {
            $nav_menu_item->load_from_id($key);
        } elseif (
            is_object($key)
            && get_class($key) === 'WP_Post'
            && property_exists($key, 'db_id')
        ) {
            $nav_menu_item->load_from_nav_menu_item($key);
        } elseif (is_object($key) && get_class($key) === 'WP_Post') {
            $nav_menu_item->load_from_post($key);
        }
        if ($nav_menu_item->get_db_id() === 0) {
            return null;
        }

        return $nav_menu_item;
    }

    /**
     * Get nav menu items
     *
     * @param array $params
     * @return array
     */
    public static function get(array $params = []): array
    {
        if (isset($params['menu_name'])) {
            return static::get_by_menu_name($params['menu_name'], $params);
        }
        if (isset($params['menu_location'])) {
            return static::get_by_menu_location($params['menu_location'], $params);
        }
        if (isset($params['menu_id'])) {
            return static::get_by_menu_id($params['menu_id'], $params);
        }

        return [];
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get nav menu items by menu name
     *
     * @see wp_get_nav_menu_object()
     * @param string $menu_name
     * @param array $params
     * @return static[]
     */
    protected static function get_by_menu_name(string $menu_name, $params = []): array
    {
        $nav_menu_object = wp_get_nav_menu_object($menu_name);

        return static::get_by_menu_id($nav_menu_object->term_id, $params);
    }

    /**
     * Get nav menu items by menu location
     *
     * @see get_nav_menu_locations()
     * @param string $menu_location
     * @param array $params
     * @return static[]
     */
    protected static function get_by_menu_location(
        string $menu_location, $params = []
    ): array
    {
        $menu_locations = get_nav_menu_locations();
        if (!isset($menu_locations[$menu_location])) {
            return [];
        }
        $menu_id = $menu_locations[$menu_location];

        return static::get_by_menu_id($menu_id, $params);
    }

    /**
     * Get nav menu items by menu ID
     *
     * @see wp_get_nav_menu_items()
     * @param int $menu_id
     * @param array $params
     * @return static[]
     */
    protected static function get_by_menu_id(int $menu_id, $params = []): array
    {
        $nav_menu_items = wp_get_nav_menu_items($menu_id, $params);
        if ($nav_menu_items === false) {
            return [];
        }

        return array_map(function($nav_menu_item) use ($menu_id) {
            $item = static::init($nav_menu_item);
            $item->set_menu_id($menu_id);
            return $item;
        }, $nav_menu_items);
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
        if ($post->post_type !== 'nav_menu_item') {
            return;
        }
        $this->load_from_post($post);
    }

    /**
     * Load instance from WP_Post object
     *
     * @see WP_Post
     * @param WP_Post $post
     */
    protected function load_from_post(WP_Post $post): void
    {
        /** @var WP_Post $wp_post */
        $wp_post = wp_setup_nav_menu_item($post);
        $this->load_from_nav_menu_item($wp_post);
    }

    /**
     * Load instance from nav menu item object
     *
     * @see WP_Post
     * @param WP_Post $nav_menu_item
     */
    protected function load_from_nav_menu_item(WP_Post $nav_menu_item): void
    {
        // WordPress adds the following properties dynamically to WP_Post
        $this->db_id = (int) $nav_menu_item->db_id;
        $this->menu_item_parent = (int) $nav_menu_item->menu_item_parent;
        $this->object_id = (int) $nav_menu_item->object_id;
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
        $this->position = (int) $nav_menu_item->menu_order;
        $this->status = $nav_menu_item->post_status;
        $this->wp_post = $nav_menu_item;
    }

    /**
     * Reload instance from database
     */
    protected function reload(): void
    {
        if (!$this->db_id) {
            return;
        }
        $this->load_from_id($this->db_id);
    }

    /************************************************************************************/
    // Action methods

    /**
     * Save nav menu item
     *
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->db_id) {
            return $this->create();
        }

        return $this->update();
    }

    /**
     * Create new nav menu item
     *
     * @see wp_update_nav_menu_item()
     * @return bool
     */
    public function create(): bool
    {
        $id = wp_update_nav_menu_item($this->menu_id, 0, [
            'menu-item-object-id' => $this->object_id,
            'menu-item-object' => $this->object,
            'menu-item-parent-id' => $this->menu_item_parent,
            'menu-item-position' => $this->position,
            'menu-item-type' => $this->type,
            'menu-item-title' => $this->title,
            'menu-item-url' => $this->url,
            'menu-item-description' => $this->description,
            'menu-item-attr-title' => $this->attr_title,
            'menu-item-target' => $this->target,
            'menu-item-classes' => implode(' ', $this->classes),
            'menu-item-xfn' => $this->xfn,
            'menu-item-status' => $this->status,
        ]);
        if (!is_int($id)) {
            return false;
        }
        $this->db_id = $id;
        $this->reload();

        return true;
    }

    /**
     * Update existing nav menu item
     *
     * @see wp_update_nav_menu_item()
     * @return bool
     */
    public function update(): bool
    {
        $id = wp_update_nav_menu_item($this->menu_id, $this->db_id, [
            'menu-item-object-id' => $this->object_id,
            'menu-item-object' => $this->object,
            'menu-item-parent-id' => $this->menu_item_parent,
            'menu-item-position' => $this->position,
            'menu-item-type' => $this->type,
            'menu-item-title' => $this->title,
            'menu-item-url' => $this->url,
            'menu-item-description' => $this->description,
            'menu-item-attr-title' => $this->attr_title,
            'menu-item-target' => $this->target,
            'menu-item-classes' => implode(' ', $this->classes),
            'menu-item-xfn' => $this->xfn,
            'menu-item-status' => $this->status,
        ]);
        if (!is_int($id)) {
            return false;
        }
        $this->db_id = $id;
        $this->reload();
    }

    /**
     * Delete nav menu item
     *
     * @see wp_delete_post()
     * @return bool
     */
    public function delete(): bool
    {
        if (!wp_delete_post($this->db_id, true)) {
            return false;
        }

        return true;
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
        $data = [];
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
        if ($this->position !== 0) {
            $data['position'] = $this->position;
        }
        if ($this->status !== '') {
            $data['status'] = $this->status;
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
     * Get status
     *
     * @return string
     */
    public function get_status(): string
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function set_status(string $status): void
    {
        $this->status = $status;
    }
}