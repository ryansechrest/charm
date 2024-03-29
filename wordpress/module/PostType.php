<?php

namespace Charm\WordPress\Module;

use WP_Post_Type;

/**
 * Class PostType
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Module
 */
class PostType
{
    /************************************************************************************/
    // Properties

    /**
     * Name
     *
     * Post type key. Must not exceed 20 characters and may only contain lowercase
     * alphanumeric characters, dashes, and underscores. See sanitize_key().
     *
     * @var string
     */
    protected string $name = '';

    /**
     * Label
     *
     * Name of the post type shown in the menu. Usually plural. Default is value of
     * $labels['name'].
     *
     * @var string
     */
    protected string $label = '';

    /**
     * Labels
     *
     * An array of labels for this post type. If not set, post labels are inherited for
     * non-hierarchical types and page labels for hierarchical ones. See
     * get_post_type_labels() for a full list of supported labels.
     *
     * @var array
     */
    protected array $labels = [];

    /**
     * Description
     *
     * A short descriptive summary of what the post type is.
     *
     * @var string
     */
    protected string $description = '';

    /**
     * Public
     *
     * Whether a post type is intended for use publicly either via the admin interface or
     * by front-end users. While the default settings of $exclude_from_search,
     * $publicly_queryable, $show_ui, and $show_in_nav_menus are inherited from public,
     * each does not rely on this relationship and controls a very specific intention.
     * Default false.
     *
     * @var bool
     */
    protected ?bool $public = null;

    /**
     * Hierarchical
     *
     * Whether the post type is hierarchical (e.g. page). Default false.
     *
     * @var bool
     */
    protected ?bool $hierarchical = null;

    /**
     * Exclude from search
     *
     * Whether to exclude posts with this post type from front end search results.
     * Default is the opposite value of $public.
     *
     * @var bool
     */
    protected ?bool $exclude_from_search = null;

    /**
     * Publicly queryable
     *
     * Whether queries can be performed on the front end for the post type as part of
     * parse_request(). Endpoints would include:
     *   ?post_type={post_type_key}
     *   ?{post_type_key}={single_post_slug}
     *   ?{post_type_query_var}={single_post_slug}
     * If not set, the default is inherited from $public.
     *
     * @var bool
     */
    protected ?bool $publicly_queryable = null;

    /**
     * Show UI
     *
     * Whether to generate and allow a UI for managing this post type in the admin.
     * Default is value of $public.
     *
     * @var bool
     */
    protected ?bool $show_ui = null;

    /**
     * Show in menu
     *
     * Where to show the post type in the admin menu. To work, $show_ui must be true.
     * If true, the post type is shown in its own top level menu. If false, no menu
     * is shown. If a string of an existing top level menu (eg. 'tools.php' or
     * 'edit.php?post_type=page'), the post type will be placed as a sub-menu of that.
     * Default is value of $show_ui.
     *
     * @var bool|string
     */
    protected string|bool $show_in_menu = '';

    /**
     * Show in nav menus
     *
     * Makes this post type available for selection in navigation menus. Default is
     * value of $public.
     *
     * @var bool
     */
    protected ?bool $show_in_nav_menus = null;

    /**
     * Show in admin bar
     *
     * Makes this post type available via the admin bar. Default is value of
     * $show_in_menu.
     *
     * @var bool
     */
    protected ?bool $show_in_admin_bar = null;

    /**
     * Show in REST
     *
     * Whether to include the post type in the REST API. Set this to true for the post
     * type to be available in the block editor.
     *
     * @var bool
     */
    protected ?bool $show_in_rest = null;

    /**
     * Rest base
     *
     * To change the base url of REST API route. Default is $post_type.
     *
     * @var string
     */
    protected string $rest_base = '';

    /**
     * REST controller class
     *
     * REST API Controller class name. Default is 'WP_REST_Posts_Controller'.
     *
     * @var string
     */
    protected string $rest_controller_class = '';

    /**
     * Menu position
     *
     * The position in the menu order the post type should appear. To work, $show_in_menu
     * must be true. Default null (at the bottom).
     *
     * @var int|null
     */
    protected ?int $menu_position = null;

    /**
     * Menu icon
     *
     * The url to the icon to be used for this menu. Pass a base64-encoded SVG using a
     * data URI, which will be colored to match the color scheme -- this should begin
     * with 'data:image/svg+xml;base64,'. Pass the name of a Dashicons helper class to
     * use a font icon, e.g. 'dashicons-chart-pie'. Pass 'none' to leave
     * div.wp-menu-image empty so an icon can be added via CSS. Defaults to use the
     * posts icon.
     *
     * @var string
     */
    protected string $menu_icon = '';

    /**
     * Capability type
     *
     * The string to use to build the read, edit, and delete capabilities. May be passed
     * as an array to allow for alternative plurals when using this argument as a base to
     * construct the capabilities, e.g. array('story', 'stories'). Default 'post'.
     *
     * @var array|string
     */
    protected array|string $capability_type = [];

    /**
     * Capabilities
     *
     * Array of capabilities for this post type. $capability_type is used as a base to
     * construct capabilities by default. See get_post_type_capabilities().
     *
     * @var array
     */
    protected array $capabilities = [];

    /**
     * Map meta cap
     *
     * Whether to use the internal default meta capability handling. Default false.
     *
     * @var bool
     */
    protected ?bool $map_meta_cap = null;

    /**
     * Supports
     *
     * Core feature(s) the post type supports. Serves as an alias for calling
     * add_post_type_support() directly. Core features include 'title', 'editor',
     * 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes',
     * 'thumbnail', 'custom-fields', and 'post-formats'. Additionally, the 'revisions'
     * feature dictates whether the post type will store revisions, and the 'comments'
     * feature dictates whether the comments count will show on the edit screen.
     * A feature can also be specified as an array of arguments to provide additional
     * information about supporting that feature.
     * Example: array( 'my_feature', array( 'field' => 'value' ) ).
     * Default is an array containing 'title' and 'editor'.
     *
     * @var array
     */
    protected array $supports = [];

    /**
     * Register meta box cb
     *
     * Provide a callback function that sets up the meta boxes for the edit form. Do
     * remove_meta_box() and add_meta_box() calls in the callback. Default null.
     *
     * @var callable
     */
    protected $register_meta_box_cb = null;

    /**
     * Taxonomies
     *
     * An array of taxonomy identifiers that will be registered for the post type.
     * Taxonomies can be registered later with register_taxonomy() or
     * register_taxonomy_for_object_type().
     *
     * @var array
     */
    protected array $taxonomies = [];

    /**
     * Has archive
     *
     * Whether there should be post type archives, or if a string, the archive slug to
     * use. Will generate the proper rewrite rules if $rewrite is enabled. Default false.
     *
     * @var bool|string
     */
    protected string|bool $has_archive = '';

    /**
     * Rewrite
     *
     * Triggers the handling of rewrites for this post type. To prevent rewrite, set to
     * false. Defaults to true, using $post_type as slug. To specify rewrite rules, an
     * array can be passed with any of these keys:
     *   'slug'  (string) Customize the permastruct slug. Defaults to $post_type key.
     *   'with_front'  (bool) Whether the permastruct should be prepended with
     *                 WP_Rewrite::$front. Default true.
     *   'feeds'  (bool) Whether the feed permastruct should be built for this post type.
     *            Default is value of $has_archive.
     *   'pages'  (bool) Whether the permastruct should provide for pagination.
     *            Default true.
     *   'ep_mask'  (const) Endpoint mask to assign. If not specified and
     *              permalink_epmask is set, inherits from $permalink_epmask. If not
     *              specified and permalink_epmask is not set, defaults to EP_PERMALINK.
     *
     * @var array
     */
    protected array $rewrite = [];

    /**
     * Query var
     *
     * Sets the query_var key for this post type. Defaults to $post_type key. If false,
     * a post type cannot be loaded at ?{query_var}={post_slug}. If specified as a
     * string, the query ?{query_var_string}={post_slug} will be valid.
     *
     * @var false|string
     */
    protected string|false $query_var = '';

    /**
     * Can export
     *
     * Whether to allow this post type to be exported. Default true.
     *
     * @var bool
     */
    protected ?bool $can_export = null;

    /**
     * Delete with user
     *
     * Whether to delete posts of this type when deleting a user. If true, posts of this
     * type belonging to the user will be moved to trash when then user is deleted. If
     * false, posts of this type belonging to the user will *not* be trashed or deleted.
     * If not set (the default), posts are trashed if post_type_supports('author').
     * Otherwise posts are not trashed or deleted. Default null.
     *
     * @var bool
     */
    protected ?bool $delete_with_user = null;

    /**
     * Post updated messages
     *
     * @var array
     */
    protected array $post_updated_messages = [];

    /*----------------------------------------------------------------------------------*/

    /**
     * WordPress post type
     *
     * @var WP_Post_Type|null
     */
    private ?WP_Post_Type $wp_post_type = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * PostType constructor
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
        if (isset($data['name'])) {
            $this->name = $data['name'];
        }
        if (isset($data['label'])) {
            $this->label = $data['label'];
        }
        if (isset($data['labels'])) {
            $this->labels = $data['labels'];
        }
        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
        if (isset($data['public'])) {
            $this->public = $data['public'];
        }
        if (isset($data['hierarchical'])) {
            $this->hierarchical = $data['hierarchical'];
        }
        if (isset($data['exclude_from_search'])) {
            $this->exclude_from_search = $data['exclude_from_search'];
        }
        if (isset($data['publicly_queryable'])) {
            $this->publicly_queryable = $data['publicly_queryable'];
        }
        if (isset($data['show_ui'])) {
            $this->show_ui = $data['show_ui'];
        }
        if (isset($data['show_in_menu'])) {
            $this->show_in_menu = $data['show_in_menu'];
        }
        if (isset($data['show_in_nav_menus'])) {
            $this->show_in_nav_menus = $data['show_in_nav_menus'];
        }
        if (isset($data['show_in_admin_bar'])) {
            $this->show_in_admin_bar = $data['show_in_admin_bar'];
        }
        if (isset($data['show_in_rest'])) {
            $this->show_in_rest = $data['show_in_rest'];
        }
        if (isset($data['rest_base'])) {
            $this->rest_base = $data['rest_base'];
        }
        if (isset($data['rest_controller_class'])) {
            $this->rest_controller_class = $data['rest_controller_class'];
        }
        if (isset($data['menu_position'])) {
            $this->menu_position = $data['menu_position'];
        }
        if (isset($data['menu_icon'])) {
            $this->menu_icon = $data['menu_icon'];
        }
        if (isset($data['capability_type'])) {
            $this->capability_type = $data['capability_type'];
        }
        if (isset($data['capabilities'])) {
            $this->capabilities = $data['capabilities'];
        }
        if (isset($data['map_meta_cap'])) {
            $this->map_meta_cap = $data['map_meta_cap'];
        }
        if (isset($data['supports'])) {
            $this->supports = $data['supports'];
        }
        if (isset($data['register_meta_box_cb'])) {
            $this->register_meta_box_cb = $data['register_meta_box_cb'];
        }
        if (isset($data['taxonomies'])) {
            $this->taxonomies = $data['taxonomies'];
        }
        if (isset($data['has_archive'])) {
            $this->has_archive = $data['has_archive'];
        }
        if (isset($data['rewrite'])) {
            $this->rewrite = $data['rewrite'];
        }
        if (isset($data['query_var'])) {
            $this->query_var = $data['query_var'];
        }
        if (isset($data['can_export'])) {
            $this->can_export = $data['can_export'];
        }
        if (isset($data['delete_with_user'])) {
            $this->delete_with_user = $data['delete_with_user'];
        }
        if (isset($data['post_updated_messages'])) {
            $this->post_updated_messages = $data['post_updated_messages'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize post type
     *
     * @see WP_Post_Type
     * @param WP_Post_Type|string $key
     * @return static|null
     */
    public static function init(WP_Post_Type|string $key): ?PostType
    {
        $post_type = new static();
        if (is_string($key)) {
            $post_type->load_from_name($key);
        } elseif (is_object($key) && get_class($key) === 'WP_Post_Type') {
            $post_type->load_from_post_type($key);
        }
        if ($post_type->get_name() === '') {
            return null;
        }

        return $post_type;
    }

    /************************************************************************************/
    // Protected load methods

    /**
     * Load instance from name
     *
     * @see get_post_type_object()
     * @param string $name
     */
    protected function load_from_name(string $name): void
    {
        if (!$wp_post_type = get_post_type_object($name)) {
            return;
        }
        $this->load_from_post_type($wp_post_type);
    }

    /**
     * Load instance from WP_Post_Type object
     *
     * @see get_all_post_type_supports()
     * @see get_object_taxonomies()
     * @param WP_Post_Type $post_type
     */
    protected function load_from_post_type(WP_Post_Type $post_type): void
    {
        $this->name = $post_type->name;
        $this->label = $post_type->label;
        $this->labels = (array) $post_type->labels;
        $this->description = $post_type->description;
        $this->public = $post_type->public;
        $this->hierarchical = $post_type->hierarchical;
        $this->exclude_from_search = $post_type->exclude_from_search;
        $this->publicly_queryable = $post_type->publicly_queryable;
        $this->show_ui = $post_type->show_ui;
        $this->show_in_menu = $post_type->show_in_menu;
        $this->show_in_nav_menus = $post_type->show_in_nav_menus;
        $this->show_in_admin_bar = $post_type->show_in_admin_bar;
        $this->show_in_rest = $post_type->show_in_rest;
        $this->rest_base = $post_type->rest_base;
        $this->rest_controller_class = $post_type->rest_controller_class;
        $this->menu_position = $post_type->menu_position;
        $this->menu_icon = $post_type->menu_icon;
        $this->capability_type = $post_type->capability_type;
        $this->capabilities = (array) $post_type->cap;
        $this->map_meta_cap = $post_type->map_meta_cap;
        $this->supports = array_keys(get_all_post_type_supports($post_type->name));
        $this->register_meta_box_cb = $post_type->register_meta_box_cb;
        $this->taxonomies = get_object_taxonomies($post_type->name);
        $this->has_archive = $post_type->has_archive;
        $this->rewrite = $post_type->rewrite;
        $this->query_var = $post_type->query_var;
        $this->can_export = $post_type->can_export;
        $this->delete_with_user = $post_type->delete_with_user;
        // Property may only exist if Charm added it, so we need to check
        if (property_exists($post_type, 'post_updated_messages')) {
            $this->post_updated_messages = $post_type->post_updated_messages;
        }
        $this->wp_post_type = $post_type;
    }

    /**
     * Reload instance from database
     */
    protected function reload(): void
    {
        if (!$this->name) {
            return;
        }
        $this->load_from_name($this->name);
    }

    /************************************************************************************/
    // Action methods

    /**
     * Register everything
     */
    public function register(): void
    {
        if ($this->name === '') {
            return;
        }
        $this->register_post_type();
        $this->register_post_updated_messages();
        $this->reload();
    }

    /**
     * Register post type
     *
     * @see add_action()
     * @see register_post_type()
     */
    public function register_post_type(): void
    {
        add_action('init', function() {
            register_post_type($this->name, $this->to_array());
        });
    }

    /**
     * Register post updated messages
     *
     * @see add_filter()
     */
    public function register_post_updated_messages(): void
    {
        add_filter('post_updated_messages', function(array $messages) {
            if (count($this->post_updated_messages) === 0) {
                return $messages;
            }
            foreach ($this->post_updated_messages as $index => $message) {
                if (isset($messages[$this->name][$index])) {
                    continue;
                }
                $messages[$this->name][$index] = $message;
            }

            return $messages;
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
        if ($this->name !== '') {
            $data['name'] = $this->name;
        }
        if ($this->label !== '') {
            $data['label'] = $this->label;
        }
        if (count($this->labels) > 0) {
            $data['labels'] = $this->labels;
        }
        if ($this->description !== '') {
            $data['description'] = $this->description;
        }
        if ($this->public !== null) {
            $data['public'] = $this->public;
        }
        if ($this->hierarchical !== null) {
            $data['hierarchical'] = $this->hierarchical;
        }
        if ($this->exclude_from_search !== null) {
            $data['exclude_from_search'] = $this->exclude_from_search;
        }
        if ($this->publicly_queryable !== null) {
            $data['publicly_queryable'] = $this->publicly_queryable;
        }
        if ($this->show_ui !== null) {
            $data['show_ui'] = $this->show_ui;
        }
        if ($this->show_in_menu !== '') {
            $data['show_in_menu'] = $this->show_in_menu;
        }
        if ($this->show_in_nav_menus !== null) {
            $data['show_in_nav_menus'] = $this->show_in_nav_menus;
        }
        if ($this->show_in_admin_bar !== null) {
            $data['show_in_admin_bar'] = $this->show_in_admin_bar;
        }
        if ($this->show_in_rest !== null) {
            $data['show_in_rest'] = $this->show_in_rest;
        }
        if ($this->rest_base !== '') {
            $data['rest_base'] = $this->rest_base;
        }
        if ($this->rest_controller_class !== '') {
            $data['rest_controller_class'] = $this->rest_controller_class;
        }
        if ($this->menu_position !== null) {
            $data['menu_position'] = $this->menu_position;
        }
        if ($this->menu_icon !== '') {
            $data['menu_icon'] = $this->menu_icon;
        }
        if (count($this->capability_type) > 0) {
            $data['capability_type'] = $this->capability_type;
        }
        if (count($this->capabilities) > 0) {
            $data['capabilities'] = $this->capabilities;
        }
        if ($this->map_meta_cap !== null) {
            $data['map_meta_cap'] = $this->map_meta_cap;
        }
        if (count($this->supports) > 0) {
            $data['supports'] = $this->supports;
        }
        if ($this->register_meta_box_cb !== null) {
            $data['register_meta_box_cb'] = $this->register_meta_box_cb;
        }
        if (count($this->taxonomies) > 0) {
            $data['taxonomies'] = $this->taxonomies;
        }
        if ($this->has_archive !== '') {
            $data['has_archive'] = $this->has_archive;
        }
        if (count($this->rewrite) > 0) {
            $data['rewrite'] = $this->rewrite;
        }
        if ($this->query_var !== '') {
            $data['query_var'] = $this->query_var;
        }
        if ($this->can_export !== null) {
            $data['can_export'] = $this->can_export;
        }
        if ($this->delete_with_user !== null) {
            $data['delete_with_user'] = $this->delete_with_user;
        }
        if (count($this->post_updated_messages) > 0) {
            $data['post_updated_messages'] = $this->post_updated_messages;
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
    // Object access methods

    /**
     * Get (or set) WordPress post type
     *
     * @param WP_Post_Type|null $post_type
     * @return WP_Post_Type
     */
    protected function wp_post_type(WP_Post_Type $post_type = null): WP_Post_Type
    {
        if ($post_type !== null) {
            $this->wp_post_type = $post_type;
        }
        return $this->wp_post_type;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get name
     *
     * @return string
     */
    public function get_name(): string
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function set_name(string $name): void
    {
        $this->name = $name;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get label
     *
     * @return string
     */
    public function get_label(): string
    {
        return $this->label;
    }

    /**
     * Set label
     *
     * @param string $label
     */
    public function set_label(string $label): void
    {
        $this->label = $label;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get labels
     *
     * @return array
     */
    public function get_labels(): array
    {
        return $this->labels;
    }

    /**
     * Set labels
     *
     * @param array $labels
     */
    public function set_labels(array $labels): void
    {
        $this->labels = $labels;
    }

    /**
     * Add individual label
     *
     * @param string $name
     * @param string $value
     */
    public function add_individual_label(string $name, string $value)
    {
        $this->labels[$name] = $value;
    }

    /**
     * Get individual label
     *
     * @param string $name
     * @return string
     */
    public function get_individual_label(string $name): string
    {
        if (!isset($this->labels[$name])) {
            return '';
        }

        return $this->labels[$name];
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
     * Is public?
     *
     * @return bool
     */
    public function is_public(): bool
    {
        return $this->public;
    }

    /**
     * Set public
     *
     * @param bool $public
     */
    public function set_public(bool $public): void
    {
        $this->public = $public;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is hierarchical?
     *
     * @return bool
     */
    public function is_hierarchical(): bool
    {
        return $this->hierarchical;
    }

    /**
     * Set hierarchical
     *
     * @param bool $hierarchical
     */
    public function set_hierarchical(bool $hierarchical): void
    {
        $this->hierarchical = $hierarchical;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is exclude from search?
     *
     * @return bool
     */
    public function is_exclude_from_search(): bool
    {
        return $this->exclude_from_search;
    }

    /**
     * Set exclude from search
     *
     * @param bool $exclude_from_search
     */
    public function set_exclude_from_search(bool $exclude_from_search): void
    {
        $this->exclude_from_search = $exclude_from_search;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is publicly queryable?
     *
     * @return bool
     */
    public function is_publicly_queryable(): bool
    {
        return $this->publicly_queryable;
    }

    /**
     * Set publicly queryable
     *
     * @param bool $publicly_queryable
     */
    public function set_publicly_queryable(bool $publicly_queryable): void
    {
        $this->publicly_queryable = $publicly_queryable;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is show UI?
     *
     * @return bool
     */
    public function is_show_ui(): bool
    {
        return $this->show_ui;
    }

    /**
     * Set show UI
     *
     * @param bool $show_ui
     */
    public function set_show_ui(bool $show_ui): void
    {
        $this->show_ui = $show_ui;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get show in menu
     *
     * @return bool|string
     */
    public function get_show_in_menu(): bool|string
    {
        return $this->show_in_menu;
    }

    /**
     * Set show in menu
     *
     * @param bool|string $show_in_menu
     */
    public function set_show_in_menu(bool|string $show_in_menu): void
    {
        $this->show_in_menu = $show_in_menu;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is show in nav menus?
     *
     * @return bool
     */
    public function is_show_in_nav_menus(): bool
    {
        return $this->show_in_nav_menus;
    }

    /**
     * Set show in nav menus
     *
     * @param bool $show_in_nav_menus
     */
    public function set_show_in_nav_menus(bool $show_in_nav_menus): void
    {
        $this->show_in_nav_menus = $show_in_nav_menus;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is show in admin bar?
     *
     * @return bool
     */
    public function is_show_in_admin_bar(): bool
    {
        return $this->show_in_admin_bar;
    }

    /**
     * Set show in admin bar
     *
     * @param bool $show_in_admin_bar
     */
    public function set_show_in_admin_bar(bool $show_in_admin_bar): void
    {
        $this->show_in_admin_bar = $show_in_admin_bar;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is show in REST?
     *
     * @return bool
     */
    public function is_show_in_rest(): bool
    {
        return $this->show_in_rest;
    }

    /**
     * Set show in REST
     *
     * @param bool $show_in_rest
     */
    public function set_show_in_rest(bool $show_in_rest): void
    {
        $this->show_in_rest = $show_in_rest;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get REST base
     *
     * @return string
     */
    public function get_rest_base(): string
    {
        return $this->rest_base;
    }

    /**
     * Set REST base
     *
     * @param string $rest_base
     */
    public function set_rest_base(string $rest_base): void
    {
        $this->rest_base = $rest_base;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get REST controller class
     *
     * @return string
     */
    public function get_rest_controller_class(): string
    {
        return $this->rest_controller_class;
    }

    /**
     * Set REST controller class
     *
     * @param string $rest_controller_class
     */
    public function set_rest_controller_class(string $rest_controller_class): void
    {
        $this->rest_controller_class = $rest_controller_class;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get menu position
     *
     * @return int
     */
    public function get_menu_position(): int
    {
        return $this->menu_position;
    }

    /**
     * Set menu position
     *
     * @param int $menu_position
     */
    public function set_menu_position(int $menu_position): void
    {
        $this->menu_position = $menu_position;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get menu icon
     *
     * @return string
     */
    public function get_menu_icon(): string
    {
        return $this->menu_icon;
    }

    /**
     * Set menu icon
     *
     * @param string $menu_icon
     */
    public function set_menu_icon(string $menu_icon): void
    {
        $this->menu_icon = $menu_icon;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get capability type
     *
     * @return array
     */
    public function get_capability_type(): array
    {
        return $this->capability_type;
    }

    /**
     * Set capability type
     *
     * @param array $capability_type
     */
    public function set_capability_type(array $capability_type): void
    {
        $this->capability_type = $capability_type;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get capabilities
     *
     * @return array
     */
    public function get_capabilities(): array
    {
        return $this->capabilities;
    }

    /**
     * Set capabilities
     *
     * @param array $capabilities
     */
    public function set_capabilities(array $capabilities): void
    {
        $this->capabilities = $capabilities;
    }

    /**
     * Add capability
     *
     * @param string $name
     * @param string $value
     */
    public function add_capability(string $name, string $value)
    {
        $this->capabilities[$name] = $value;
    }

    /**
     * Get capability
     *
     * @param string $name
     * @return string
     */
    public function get_capability(string $name): string
    {
        if (!isset($this->capabilities[$name])) {
            return '';
        }

        return $this->capabilities[$name];
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is map meta cap?
     *
     * @return bool
     */
    public function is_map_meta_cap(): bool
    {
        return $this->map_meta_cap;
    }

    /**
     * Set map meta cap
     *
     * @param bool $map_meta_cap
     */
    public function set_map_meta_cap(bool $map_meta_cap): void
    {
        $this->map_meta_cap = $map_meta_cap;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get supports
     *
     * @return array
     */
    public function get_supports(): array
    {
        return $this->supports;
    }

    /**
     * Set supports
     *
     * @param array $supports
     */
    public function set_supports(array $supports): void
    {
        $this->supports = $supports;
    }

    /**
     * Add support
     *
     * @param string $name
     */
    public function add_support(string $name)
    {
        $this->supports[] = $name;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get register meta box callback
     *
     * @return callable
     */
    public function get_register_meta_box_cb(): callable
    {
        return $this->register_meta_box_cb;
    }

    /**
     * Set register meta box callback
     *
     * @param callable $register_meta_box_cb
     */
    public function set_register_meta_box_cb(callable $register_meta_box_cb): void
    {
        $this->register_meta_box_cb = $register_meta_box_cb;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get taxonomies
     *
     * @return array
     */
    public function get_taxonomies(): array
    {
        return $this->taxonomies;
    }

    /**
     * Set taxonomies
     *
     * @param array $taxonomies
     */
    public function set_taxonomies(array $taxonomies): void
    {
        $this->taxonomies = $taxonomies;
    }

    /**
     * Add taxonomy
     *
     * @param string $name
     */
    public function add_taxonomy(string $name)
    {
        $this->taxonomies[] = $name;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get has archive
     *
     * @return bool|string
     */
    public function get_has_archive(): bool|string
    {
        return $this->has_archive;
    }

    /**
     * Set has archive
     *
     * @param bool|string $has_archive
     */
    public function set_has_archive(bool|string $has_archive): void
    {
        $this->has_archive = $has_archive;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get rewrite
     *
     * @return array
     */
    public function get_rewrite(): array
    {
        return $this->rewrite;
    }

    /**
     * Set rewrite
     *
     * @param array $rewrite
     */
    public function set_rewrite(array $rewrite): void
    {
        $this->rewrite = $rewrite;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get query var
     *
     * @return bool|string
     */
    public function get_query_var(): bool|string
    {
        return $this->query_var;
    }

    /**
     * Set query var
     *
     * @param bool|string $query_var
     */
    public function set_query_var(bool|string $query_var): void
    {
        $this->query_var = $query_var;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is can export?
     *
     * @return bool
     */
    public function is_can_export(): bool
    {
        return $this->can_export;
    }

    /**
     * Set can export
     *
     * @param bool $can_export
     */
    public function set_can_export(bool $can_export): void
    {
        $this->can_export = $can_export;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is delete with user?
     *
     * @return bool
     */
    public function is_delete_with_user(): ?bool
    {
        return $this->delete_with_user;
    }

    /**
     * Set delete with user
     *
     * @param bool $delete_with_user
     */
    public function set_delete_with_user(bool $delete_with_user): void
    {
        $this->delete_with_user = $delete_with_user;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get post updated messages
     *
     * @return array
     */
    public function get_post_updated_messages(): array
    {
        return $this->post_updated_messages;
    }

    /**
     * Set post updated messages
     *
     * @param array $post_updated_messages
     */
    public function set_post_updated_messages(array $post_updated_messages)
    {
        $this->post_updated_messages = $post_updated_messages;
    }

    /**
     * Add post updated message
     *
     * @param int $key
     * @param string $message
     */
    public function add_post_updated_message(int $key, string $message)
    {
        $this->post_updated_messages[$key] = $message;
    }
}