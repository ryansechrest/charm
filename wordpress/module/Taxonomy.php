<?php

namespace Charm\WordPress\Module;

use WP_Taxonomy;

/**
 * Class Taxonomy
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Module
 */
class Taxonomy
{
    /************************************************************************************/
    // Properties

    /**
     * Name
     *
     * The name of the taxonomy. Name should only contain lowercase letters and the
     * underscore character, and not be more than 32 characters long (database structure
     * restriction).
     *
     * @var string
     */
    protected string $name = '';

    /**
     * Object type
     *
     * Name of the object type for the taxonomy object. Object-types can be built-in
     * Post Type or any Custom Post Type that may be registered.
     *
     * Setting explicitly to null registers the taxonomy but doesn't associate it with
     * any objects, so it won't be directly available within the Admin UI. You will need
     * to manually register it using the 'taxonomy' parameter (passed through $args) when
     * registering a custom post_type (see register_post_type()), or using
     * register_taxonomy_for_object_type().
     *
     * @var array|string|null
     */
    protected string|array|null $object_type = '';

    /**
     * Label
     *
     * A plural descriptive name for the taxonomy marked for translation.
     *
     * @var string
     */
    protected string $label = '';

    /**
     * Labels
     *
     * An array of labels for this taxonomy. By default tag labels are used for
     * non-hierarchical types and category labels for hierarchical ones.
     *
     * @var array
     */
    protected array $labels = [];

    /**
     * Public
     *
     * Whether a taxonomy is intended for use publicly either via the admin interface or
     * by front-end users. The default settings of `$publicly_queryable`, `$show_ui`, and
     * `$show_in_nav_menus` are inherited from `$public`.
     *
     * @var bool
     */
    protected ?bool $public = null;

    /**
     * Publicly queryable
     *
     * Whether the taxonomy is publicly queryable.
     *
     * @var bool
     */
    protected ?bool $publicly_queryable = null;

    /**
     * Show UI
     *
     * Whether to generate a default UI for managing this taxonomy.
     *
     * @var bool
     */
    protected ?bool $show_ui = null;

    /**
     * Show in menu
     *
     * Where to show the taxonomy in the admin menu. show_ui must be true.
     *
     * @var bool
     */
    protected ?bool $show_in_menu = null;

    /**
     * Show in nav menus
     *
     * True makes this taxonomy available for selection in navigation menus.
     *
     * @var bool
     */
    protected ?bool $show_in_nav_menus = null;

    /**
     * Show in REST
     *
     * Whether to include the taxonomy in the REST API. You will need to set this to true
     * in order to use the taxonomy in your gutenberg metablock.
     *
     * @var bool
     */
    protected ?bool $show_in_rest = null;

    /**
     * REST base
     *
     * To change the base url of REST API route.
     *
     * @var string
     */
    protected string $rest_base = '';

    /**
     * REST controller class
     *
     * REST API Controller class name.
     *
     * @var string
     */
    protected string $rest_controller_class = '';

    /**
     * Show tag cloud
     *
     * Whether to allow the Tag Cloud widget to use this taxonomy.
     *
     * @var bool
     */
    protected ?bool $show_tagcloud = null;

    /**
     * Show in quick edit
     *
     * Whether to show the taxonomy in the quick/bulk edit panel. (Available since 4.2)
     *
     * @var bool
     */
    protected ?bool $show_in_quick_edit = null;

    /**
     * Meta box callback
     *
     * Provide a callback function name for the meta box display. (Available since 3.8)
     *
     * Defaults to the categories meta box (post_categories_meta_box() in meta-boxes.php)
     * for hierarchical taxonomies and the tags meta box (post_tags_meta_box()) for
     * non-hierarchical taxonomies. No meta box is shown if set to false.
     *
     * @var callable
     */
    protected $meta_box_cb = null;

    /**
     * Meta box sanitization callback
     *
     * @var callable
     */
    protected $meta_box_sanitize_cb = null;

    /**
     * Show admin column
     *
     * Whether to allow automatic creation of taxonomy columns on associated post-types
     * table. (Available since 3.5)
     *
     * @var bool
     */
    protected ?bool $show_admin_column = null;

    /**
     * Description
     *
     * Include a description of the taxonomy.
     *
     * @var string
     */
    protected string $description = '';

    /**
     * Hierarchical
     *
     * Is this taxonomy hierarchical (have descendants) like categories or not
     * hierarchical like tags.
     *
     * Hierarchical taxonomies will have a list with checkboxes to select an existing
     * category in the taxonomy admin box on the post edit page (like default post
     * categories). Non-hierarchical taxonomies will just have an empty text field to
     * type-in taxonomy terms to associate with the post (like default post tags).
     *
     * @var bool
     */
    protected ?bool $hierarchical = null;

    /**
     * Update count callback
     *
     * A function name that will be called when the count of an associated $object_type,
     * such as post, is updated. Works much like a hook.
     *
     * While the default is '', when actually performing the count update in
     * wp_update_term_count_now(), if the taxonomy is only attached to post types
     * (as opposed to other WordPress objects, like user), the built-in
     * _update_post_term_count() function will be used to count only published posts
     * associated with that term, otherwise _update_generic_term_count() will be used
     * instead, that does no such checking.
     *
     * @var callable
     */
    protected $update_count_callback = '';

    /**
     * Query var
     *
     * False to disable the query_var, set as string to use custom query_var instead of
     * default which is $taxonomy, the taxonomy's "name". True is not seen as a valid
     * entry and will result in 404 issues.
     *
     * The query_var is used for direct queries through WP_Query like
     * new WP_Query(array('people'=>$person_name)) and URL queries
     * like /?people=$person_name. Setting query_var to false will disable these methods,
     * but you can still fetch posts with an explicit WP_Query taxonomy query like
     * WP_Query(array('taxonomy'=>'people', 'term'=>$person_name)).
     *
     * @var false|string
     */
    protected string|false $query_var = '';

    /**
     * Rewrite
     *
     * Set to false to prevent automatic URL rewriting a.k.a. "pretty permalinks". Pass
     * an $args array to override default URL settings for permalinks as outlined below:
     *
     *   'slug' - Used as pretty permalink text (i.e. /tag/) - defaults to $taxonomy
     *            (taxonomy's name slug)
     *   'with_front' - allowing permalinks to be prepended with front base
     *                  defaults to true
     *   'hierarchical' - true or false allow hierarchical urls (implemented in
     *                    Version 3.1) - defaults to false
     *   'ep_mask' - (Required for pretty permalinks) Assign an endpoint mask for this
     *               taxonomy - defaults to EP_NONE. If you do not specify the EP_MASK,
     *               pretty permalinks will not work.
     *
     * You may need to flush the rewrite rules after changing this. You can do it
     * manually by going to the Permalink Settings page and re-saving the rules -- you
     * don't need to change them -- or by calling $wp_rewrite->flush_rules(). You should
     * only flush the rules once after the taxonomy has been created, not every time the
     * plugin/theme loads.
     *
     * @var array
     */
    protected array $rewrite = [];

    /**
     * Capabilities
     *
     * An array of the capabilities for this taxonomy.
     *
     * @var array
     */
    protected ?array $capabilities = null;

    /*----------------------------------------------------------------------------------*/

    /**
     * WordPress taxonomy
     *
     * @var WP_Taxonomy|null
     */
    private ?WP_Taxonomy $wp_taxonomy = null;

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
        if (isset($data['object_type'])) {
            $this->object_type = $data['object_type'];
        }
        if (isset($data['label'])) {
            $this->label = $data['label'];
        }
        if (isset($data['labels'])) {
            $this->labels = $data['labels'];
        }
        if (isset($data['public'])) {
            $this->public = $data['public'];
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
        if (isset($data['show_in_rest'])) {
            $this->show_in_rest = $data['show_in_rest'];
        }
        if (isset($data['rest_base'])) {
            $this->rest_base = $data['rest_base'];
        }
        if (isset($data['rest_controller_class'])) {
            $this->rest_controller_class = $data['rest_controller_class'];
        }
        if (isset($data['show_tagcloud'])) {
            $this->show_tagcloud = $data['show_tagcloud'];
        }
        if (isset($data['show_in_quick_edit'])) {
            $this->show_in_quick_edit = $data['show_in_quick_edit'];
        }
        if (isset($data['meta_box_cb'])) {
            $this->meta_box_cb = $data['meta_box_cb'];
        }
        if (isset($data['meta_box_sanitize_cb'])) {
            $this->meta_box_sanitize_cb = $data['meta_box_sanitize_cb'];
        }
        if (isset($data['show_admin_column'])) {
            $this->show_admin_column = $data['show_admin_column'];
        }
        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
        if (isset($data['hierarchical'])) {
            $this->hierarchical = $data['hierarchical'];
        }
        if (isset($data['update_count_callback'])) {
            $this->update_count_callback = $data['update_count_callback'];
        }
        if (isset($data['query_var'])) {
            $this->query_var = $data['query_var'];
        }
        if (isset($data['rewrite'])) {
            $this->rewrite = $data['rewrite'];
        }
        if (isset($data['capabilities'])) {
            $this->capabilities = $data['capabilities'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize taxonomy
     *
     * @see WP_Taxonomy
     * @param WP_Taxonomy|string $key
     * @return static|null
     */
    public static function init(WP_Taxonomy|string $key): ?Taxonomy
    {
        $taxonomy = new static();
        if (is_string($key)) {
            $taxonomy->load_from_name($key);
        } elseif (is_object($key) && get_class($key) === 'WP_Taxonomy') {
            $taxonomy->load_from_taxonomy($key);
        }
        if ($taxonomy->get_name() === '') {
            return null;
        }

        return $taxonomy;
    }

    /**
     * Get taxonomies
     *
     * @see get_taxonomies()
     * @param array $params
     * @return static[]
     */
    public static function get(array $params = []): array
    {
        $taxonomies = [];
        $wp_taxonomies = get_taxonomies([], 'objects');
        if (!isset($params['_builtin'])) {
            $params['_builtin'] = false;
        }
        foreach ($wp_taxonomies as $name => $wp_taxonomy) {
            $tax_matches_query = true;
            foreach ($params as $key => $value) {
                if (!property_exists($wp_taxonomy, $key)) {
                    continue;
                }
                if ($wp_taxonomy->$key !== $value) {
                    $tax_matches_query = false;
                    break;
                }
            }
            if ($tax_matches_query) {
                $taxonomy = static::init($wp_taxonomy);
                $taxonomy->wp_taxonomy($wp_taxonomy);
                $taxonomies[] = $taxonomy;
            }
        }

        return $taxonomies;
    }

    /************************************************************************************/
    // Protected load methods

    /**
     * Load instance from name
     *
     * @see get_taxonomy()
     * @param string $name
     */
    protected function load_from_name(string $name): void
    {
        if (!$wp_taxonomy = get_taxonomy($name)) {
            return;
        }
        $this->load_from_taxonomy($wp_taxonomy);
    }

    /**
     * Load instance from WP_User object
     *
     * @see WP_Taxonomy
     * @param WP_Taxonomy $taxonomy
     */
    protected function load_from_taxonomy(WP_Taxonomy $taxonomy): void
    {
        $this->name = $taxonomy->name;
        $this->object_type = $taxonomy->object_type;
        $this->label = $taxonomy->label;
        $this->labels = (array) $taxonomy->labels;
        $this->public = $taxonomy->public;
        $this->publicly_queryable = $taxonomy->publicly_queryable;
        $this->show_ui = $taxonomy->show_ui;
        $this->show_in_menu = $taxonomy->show_in_menu;
        $this->show_in_nav_menus = $taxonomy->show_in_nav_menus;
        $this->show_in_rest = $taxonomy->show_in_rest;
        $this->rest_base = $taxonomy->rest_base;
        $this->rest_controller_class = $taxonomy->rest_controller_class;
        $this->show_tagcloud = $taxonomy->show_tagcloud;
        $this->show_in_quick_edit = $taxonomy->show_in_quick_edit;
        $this->meta_box_cb = $taxonomy->meta_box_cb;
        $this->meta_box_sanitize_cb = $taxonomy->meta_box_sanitize_cb;
        $this->show_admin_column = $taxonomy->show_admin_column;
        $this->description = $taxonomy->description;
        $this->hierarchical = $taxonomy->hierarchical;
        $this->update_count_callback = $taxonomy->update_count_callback;
        $this->query_var = $taxonomy->query_var;
        $this->rewrite = $taxonomy->rewrite;
        $this->capabilities = (array) $taxonomy->cap;
        $this->wp_taxonomy = $taxonomy;
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
        $this->register_taxonomy();
        $this->reload();
    }

    /**
     * Register post type
     *
     * @see add_action()
     * @see register_taxonomy()
     */
    public function register_taxonomy(): void
    {
        add_action('init', function() {
            register_taxonomy($this->name, $this->object_type, $this->to_array());
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
        if ($this->object_type !== '') {
            $data['object_type'] = $this->object_type;
        }
        if ($this->label !== '') {
            $data['label'] = $this->label;
        }
        if (count($this->labels) > 0) {
            $data['labels'] = $this->labels;
        }
        if ($this->public !== null) {
            $data['public'] = $this->public;
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
        if ($this->show_in_rest !== null) {
            $data['show_in_rest'] = $this->show_in_rest;
        }
        if ($this->rest_base !== '') {
            $data['rest_base'] = $this->rest_base;
        }
        if ($this->rest_controller_class !== '') {
            $data['rest_controller_class'] = $this->rest_controller_class;
        }
        if ($this->show_tagcloud !== null) {
            $data['show_tagcloud'] = $this->show_tagcloud;
        }
        if ($this->show_in_quick_edit !== null) {
            $data['show_in_quick_edit'] = $this->show_in_quick_edit;
        }
        if ($this->meta_box_cb !== null) {
            $data['meta_box_cb'] = $this->meta_box_cb;
        }
        if ($this->show_admin_column !== null) {
            $data['show_admin_column'] = $this->show_admin_column;
        }
        if ($this->description !== '') {
            $data['description'] = $this->description;
        }
        if ($this->hierarchical !== null) {
            $data['hierarchical'] = $this->hierarchical;
        }
        if ($this->update_count_callback === '') {
            $data['update_count_callback'] = $this->update_count_callback;
        }
        if ($this->query_var !== '') {
            $data['query_var'] = $this->query_var;
        }
        if (count($this->rewrite) > 0) {
            $data['rewrite'] = $this->rewrite;
        }
        if ($this->capabilities !== null) {
            $data['capabilities'] = $this->capabilities;
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
     * Get (or set) WordPress taxonomy
     *
     * @param WP_Taxonomy|null $taxonomy
     * @return WP_Taxonomy
     */
    protected function wp_taxonomy(WP_Taxonomy $taxonomy = null): WP_Taxonomy
    {
        if ($taxonomy !== null) {
            $this->wp_taxonomy = $taxonomy;
        }

        return $this->wp_taxonomy;
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
     * Get object type
     *
     * @return array|string|null
     */
    public function get_object_type(): array|string|null
    {
        return $this->object_type;
    }

    /**
     * Set object type
     *
     * @param array|string|null $object_type
     */
    public function set_object_type(array|string|null $object_type): void
    {
        $this->object_type = $object_type;
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
    public function add_individual_label(string $name, string $value): void
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
     * Is show in menu?
     *
     * @return bool
     */
    public function is_show_in_menu(): bool
    {
        return $this->show_in_menu;
    }

    /**
     * Show in menu
     *
     * @param bool $show_in_menu
     */
    public function set_show_in_menu(bool $show_in_menu): void
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
     * Is show tag cloud?
     *
     * @return bool
     */
    public function is_show_tagcloud(): bool
    {
        return $this->show_tagcloud;
    }

    /**
     * Set show tag cloud
     *
     * @param bool $show_tagcloud
     */
    public function set_show_tagcloud(bool $show_tagcloud): void
    {
        $this->show_tagcloud = $show_tagcloud;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is show in quick edit?
     *
     * @return bool
     */
    public function is_show_in_quick_edit(): bool
    {
        return $this->show_in_quick_edit;
    }

    /**
     * Set show in quick edit
     *
     * @param bool $show_in_quick_edit
     */
    public function set_show_in_quick_edit(bool $show_in_quick_edit): void
    {
        $this->show_in_quick_edit = $show_in_quick_edit;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get meta box callback
     *
     * @return callable
     */
    public function get_meta_box_cb(): callable
    {
        return $this->meta_box_cb;
    }

    /**
     * Set meta box callback
     *
     * @param callable $meta_box_cb
     */
    public function set_meta_box_cb(callable $meta_box_cb): void
    {
        $this->meta_box_cb = $meta_box_cb;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is show admin column?
     *
     * @return bool
     */
    public function is_show_admin_column(): bool
    {
        return $this->show_admin_column;
    }

    /**
     * Set show admin column
     *
     * @param bool $show_admin_column
     */
    public function set_show_admin_column(bool $show_admin_column): void
    {
        $this->show_admin_column = $show_admin_column;
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
     * Get update count callback
     *
     * @return callable
     */
    public function get_update_count_callback(): callable
    {
        return $this->update_count_callback;
    }

    /**
     * Set update count callback
     *
     * @param callable $update_count_callback
     */
    public function set_update_count_callback(callable $update_count_callback): void
    {
        $this->update_count_callback = $update_count_callback;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get query var
     *
     * @return false|string
     */
    public function get_query_var()
    {
        return $this->query_var;
    }

    /**
     * Set query var
     *
     * @param false|string $query_var
     */
    public function set_query_var($query_var): void
    {
        $this->query_var = $query_var;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * @return array
     */
    public function get_rewrite(): array
    {
        return $this->rewrite;
    }

    /**
     * @param array $rewrite
     */
    public function set_rewrite(array $rewrite): void
    {
        $this->rewrite = $rewrite;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * @return array
     */
    public function get_capabilities(): array
    {
        return $this->capabilities;
    }

    /**
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
        if ($this->capabilities === null) {
            $this->capabilities = [];
        }
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
        if ($this->capabilities === null) {
            return '';
        }
        if (!isset($this->capabilities[$name])) {
            return '';
        }

        return $this->capabilities[$name];
    }
}