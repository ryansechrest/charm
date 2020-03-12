<?php

namespace Charm\WordPress\Module;

/**
 * Class PostType
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Module
 */
class PostType
{
    /**
     * Post type
     *
     * Post type key. Must not exceed 20 characters and may only contain lowercase
     * alphanumeric characters, dashes, and underscores. See sanitize_key().
     *
     * @var string
     */
    protected $post_type = '';

    /**
     * Label
     *
     * Name of the post type shown in the menu. Usually plural. Default is value of
     * $labels['name'].
     *
     * @var string
     */
    protected $label = '';

    /**
     * Labels
     *
     * An array of labels for this post type. If not set, post labels are inherited for
     * non-hierarchical types and page labels for hierarchical ones. See
     * get_post_type_labels() for a full list of supported labels.
     *
     * @var array
     */
    protected $labels = [];

    /**
     * Description
     *
     * A short descriptive summary of what the post type is.
     *
     * @var string
     */
    protected $description = '';

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
    protected $public = null;

    /**
     * Hierarchical
     *
     * Whether the post type is hierarchical (e.g. page). Default false.
     *
     * @var bool
     */
    protected $hierarchical = null;

    /**
     * Exclude from search
     *
     * Whether to exclude posts with this post type from front end search results.
     * Default is the opposite value of $public.
     *
     * @var bool
     */
    protected $exclude_from_search = null;

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
    protected $publicly_queryable = null;

    /**
     * Show UI
     *
     * Whether to generate and allow a UI for managing this post type in the admin.
     * Default is value of $public.
     *
     * @var bool
     */
    protected $show_ui = null;

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
    protected $show_in_menu = '';

    /**
     * Show in nav menus
     *
     * Makes this post type available for selection in navigation menus. Default is
     * value of $public.
     *
     * @var bool
     */
    protected $show_in_nav_menus = null;

    /**
     * Show in admin bar
     *
     * Makes this post type available via the admin bar. Default is value of
     * $show_in_menu.
     *
     * @var bool
     */
    protected $show_in_admin_bar = null;

    /**
     * Show in REST
     *
     * Whether to include the post type in the REST API. Set this to true for the post
     * type to be available in the block editor.
     *
     * @var bool
     */
    protected $show_in_rest = null;

    /**
     * Rest base
     *
     * To change the base url of REST API route. Default is $post_type.
     *
     * @var string
     */
    protected $rest_base = '';

    /**
     * REST controller class
     *
     * REST API Controller class name. Default is 'WP_REST_Posts_Controller'.
     *
     * @var string
     */
    protected $rest_controller_class = '';

    /**
     * Menu position
     *
     * The position in the menu order the post type should appear. To work, $show_in_menu
     * must be true. Default null (at the bottom).
     *
     * @var int
     */
    protected $menu_position = null;

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
    protected $menu_icon = '';

    /**
     * Capability type
     *
     * The string to use to build the read, edit, and delete capabilities. May be passed
     * as an array to allow for alternative plurals when using this argument as a base to
     * construct the capabilities, e.g. array('story', 'stories'). Default 'post'.
     *
     * @var string
     */
    protected $capability_type = '';

    /**
     * Capabilities
     *
     * Array of capabilities for this post type. $capability_type is used as a base to
     * construct capabilities by default. See get_post_type_capabilities().
     *
     * @var array
     */
    protected $capabilities = [];

    /**
     * Map meta cap
     *
     * Whether to use the internal default meta capability handling. Default false.
     *
     * @var bool
     */
    protected $map_meta_cap = null;

    /**
     * Supports
     *
     * Core feature(s) the post type supports. Serves as an alias for calling
     * add_post_type_support() directly. Core features include 'title', 'editor',
     * 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes',
     * thumbnail', 'custom-fields', and 'post-formats'. Additionally, the 'revisions'
     * feature dictates whether the post type will store revisions, and the 'comments'
     * feature dictates whether the comments count will show on the edit screen.
     * A feature can also be specified as an array of arguments to provide additional
     * information about supporting that feature.
     * Example: array( 'my_feature', array( 'field' => 'value' ) ).
     * Default is an array containing 'title' and 'editor'.
     *
     * @var array
     */
    protected $supports = [];

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
    protected $taxonomies = [];

    /**
     * Has archive
     *
     * Whether there should be post type archives, or if a string, the archive slug to
     * use. Will generate the proper rewrite rules if $rewrite is enabled. Default false.
     *
     * @var bool|string
     */
    protected $has_archive = '';

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
    protected $rewrite = [];

    /**
     * Query var
     *
     * Sets the query_var key for this post type. Defaults to $post_type key. If false,
     * a post type cannot be loaded at ?{query_var}={post_slug}. If specified as a
     * string, the query ?{query_var_string}={post_slug} will be valid.
     *
     * @var bool|string
     */
    protected $query_var = '';

    /**
     * Can export
     *
     * Whether to allow this post type to be exported. Default true.
     *
     * @var bool
     */
    protected $can_export = null;

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
    protected $delete_with_user = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * PostType constructor
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (!is_array($data)) {
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
        if (isset($data['post_type'])) {
            $this->post_type = $data['post_type'];
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
    }

    /************************************************************************************/
    // Action methods

    /**
     * Register everything
     */
    public function register(): void
    {
        if ($this->post_type === '') {
            return;
        }
        $this->register_post_type();
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
            register_post_type($this->post_type, $this->to_array());
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
        if ($this->post_type !== '') {
            $data['post_type'] = $this->post_type;
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
        if ($this->capability_type !== '') {
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
     * Get post type
     *
     * @return string
     */
    public function get_post_type(): string
    {
        return $this->post_type;
    }

    /**
     * Set post type
     *
     * @param string $post_type
     */
    public function set_post_type(string $post_type): void
    {
        $this->post_type = $post_type;
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
    public function get_show_in_menu()
    {
        return $this->show_in_menu;
    }

    /**
     * Set show in menu
     *
     * @param bool|string $show_in_menu
     */
    public function set_show_in_menu($show_in_menu): void
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
     * @return string
     */
    public function get_capability_type(): string
    {
        return $this->capability_type;
    }

    /**
     * Set capability type
     *
     * @param string $capability_type
     */
    public function set_capability_type(string $capability_type): void
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
     * @param array|string $name
     */
    public function add_support(string $name)
    {
        $this->supports[] = $name;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get register meta box cb
     *
     * @return callable
     */
    public function get_register_meta_box_cb(): callable
    {
        return $this->register_meta_box_cb;
    }

    /**
     * Set register meta box cb
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
    public function get_has_archive()
    {
        return $this->has_archive;
    }

    /**
     * Set has archive
     *
     * @param bool|string $has_archive
     */
    public function set_has_archive($has_archive): void
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
    public function get_query_var()
    {
        return $this->query_var;
    }

    /**
     * Set query var
     *
     * @param bool|string $query_var
     */
    public function set_query_var($query_var): void
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
    public function is_delete_with_user()
    {
        return $this->delete_with_user;
    }

    /**
     * Set delete with user
     *
     * @param bool $delete_with_user
     */
    public function set_delete_with_user($delete_with_user): void
    {
        $this->delete_with_user = $delete_with_user;
    }
}