<?php

namespace Charm\Module;

use Charm\WordPress\Module\PostType as WpPostType;

/**
 * Class PostType
 *
 * @author Ryan Sechrest
 * @package Charm\Module
 */
class PostType extends WpPostType
{
    /**
     * Post messages
     *
     * @var array
     */
    protected $post_messages = [];

    /************************************************************************************/
    // Default constructor and load method

    /**
     * PostType constructor
     *
     * @param array $data
     * @param array $methods
     */
    public function __construct(array $data, array $methods)
    {
        parent::__construct($data);
        foreach ($methods as $method) {
            if (!method_exists($this, $method)) {
                continue;
            }
            $this->$method();
        }
    }

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['post_messages'])) {
            $this->post_messages = $data['post_messages'];
        }
        parent::load($data);
    }

    /************************************************************************************/
    // Action methods

    /**
     * Initialize properties to WordPress defaults
     */
    public function initialize(): void {
        if ($this->public === null) {
            $this->public = false;
        }
        if ($this->hierarchical === null) {
            $this->hierarchical = false;
        }
        if ($this->exclude_from_search === null) {
            $this->exclude_from_search = !$this->public;
        }
        if ($this->publicly_queryable === null) {
            $this->publicly_queryable = $this->public;
        }
        if ($this->show_ui === null) {
            $this->show_ui = $this->public;
        }
        if ($this->show_in_menu === '') {
            $this->show_in_menu = $this->show_ui;
        }
        if ($this->show_in_nav_menus === null) {
            $this->show_in_nav_menus = $this->public;
        }
        if ($this->show_in_admin_bar === null) {
            $this->show_in_admin_bar = $this->show_in_menu;
        }
        if ($this->show_in_rest === null) {
            $this->show_in_rest = false;
        }
        if ($this->rest_base === '') {
            $this->rest_base = $this->post_type;
        }
        if ($this->rest_controller_class === '') {
            $this->rest_controller_class = 'WP_REST_Posts_Controller';
        }
        if ($this->capability_type === '') {
            $this->capability_type = 'post';
        }
        if ($this->map_meta_cap === null) {
            $this->map_meta_cap = false;
        }
        if (count($this->supports) === 0) {
            $this->supports = ['title', 'editor'];
        }
        if ($this->has_archive === '') {
            $this->has_archive = false;
        }
        if (count($this->rewrite) === 0) {
            $this->rewrite = [
                'slug' => $this->post_type,
                'with_front' => true,
                'feeds' => $this->has_archive,
                'pages' => true,
                'ep_mask' => EP_PERMALINK,
            ];
        }
        if ($this->query_var === '') {
            $this->query_var = $this->post_type;
        }
        if ($this->can_export === null) {
            $this->can_export = true;
        }
    }

    /**
     * Autocomplete everything
     */
    public function autocomplete(): void
    {
        $this->autocomplete_labels();
        $this->autocomplete_post_messages();
    }

    /**
     * Autocomplete labels
     */
    public function autocomplete_labels(): void
    {
        $singular = ucwords(str_replace('_', ' ', $this->post_type));
        $plural = $singular . 's';
        if ($this->label) {
            $singular = $this->label;
            $plural = $singular . 's';
        }
        if (isset($this->labels['name'])) {
            $plural = $this->labels['name'];
        }
        if (isset($this->labels['singular_name'])) {
            $singular = $this->labels['singular_name'];
        }
        if (!$this->label) {
            $this->label = $plural;
        }
        $this->fill_labels($singular, $plural);
    }

    /**
     * Autocomplete post messages
     */
    public function autocomplete_post_messages(): void
    {
        $singular = $this->get_individual_label('singular_name');
        $this->fill_post_messages($singular);
    }

    /**
     * Register everything
     */
    public function register(): void
    {
        $this->register_post_messages();
        parent::register();
    }

    /**
     * Register post messages
     *
     * @see add_filter()
     */
    public function register_post_messages(): void
    {
        add_filter('post_updated_messages', function(array $messages) {
            $messages[$this->post_type] = $this->post_messages;

            return $messages;
        });
    }

    /************************************************************************************/
    // Private fill and format methods

    /**
     * Fill labels into $this->labels
     *
     * @param string $singular
     * @param string $plural
     */
    private function fill_labels(string $singular, string $plural): void
    {
        $this->fill_label('name', $plural);
        $this->fill_label('singular_name', $singular);
        $this->fill_label('add_new_item', $singular);
        $this->fill_label('edit_item', $singular);
        $this->fill_label('new_item', $singular);
        $this->fill_label('view_item', $singular);
        $this->fill_label('view_items', $plural);
        $this->fill_label('search_items', $plural);
        $this->fill_label('not_found', strtolower($plural));
        $this->fill_label('not_found_in_trash', strtolower($plural));
        $this->fill_label('parent_item_colon', $singular);
        $this->fill_label('all_items', $plural);
        $this->fill_label('archives', $plural);
        $this->fill_label('attributes', $singular);
        $this->fill_label('insert_into_item', $singular);
        $this->fill_label('uploaded_to_this_item', strtolower($singular));
        $this->fill_label('menu_name', $plural);
        $this->fill_label('filter_items_list', strtolower($plural));
        $this->fill_label('items_list_navigation', $plural);
        $this->fill_label('items_list', $plural);
        $this->fill_label('item_published', $singular);
        $this->fill_label('item_published_privately', $singular);
        $this->fill_label('item_reverted_to_draft', $singular);
        $this->fill_label('item_scheduled', $singular);
        $this->fill_label('item_updated', $singular);
    }

    /**
     * Fill specified label (if not already set)
     *
     * @param string $key
     * @param string $noun
     */
    private function fill_label(string $key, string $noun): void
    {
        if (isset($this->labels[$key])) {
            return;
        }
        $label = sprintf($this->get_label_format($key), $noun);
        $this->add_individual_label(
            $key, _x($label, 'Post Type: ' . $this->post_type, 'charm')
        );
    }

    /**
     * Get label format
     *
     * @param string $name
     * @return string
     */
    private function get_label_format(string $name): string
    {
        $formats = [
            'name' => '%s',
            'singular_name' => '%s',
            'add_new_item' => 'Add New %s',
            'edit_item' => 'Edit %s',
            'new_item' => 'New %s',
            'view_item' => 'View %s',
            'view_items' => 'View %s',
            'search_items' => 'Search %s',
            'not_found' => 'No %s found.',
            'not_found_in_trash' => 'No %s found in trash.',
            'parent_item_colon' => 'Parent %s:',
            'all_items' => 'All %s',
            'archives' => '%s Archives',
            'attributes' => '%s Attributes',
            'insert_into_item' => 'Embed into %s',
            'uploaded_to_this_item' => 'Uploaded to this %s',
            'menu_name' => '%s',
            'filter_items_list' => 'Filter %s list',
            'items_list_navigation' => '%s list navigation',
            'items_list' => '%s list',
            'item_published' => '%s published.',
            'item_published_privately' => '%s published privately.',
            'item_reverted_to_draft' => '%s reverted to draft',
            'item_scheduled' => '%s scheduled to be published.',
            'item_updated' => '%s updated.',
        ];
        if (!isset($formats[$name])) {
            return 'No message defined.';
        }

        return $formats[$name];
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Fill post messages into $this->post_messages
     *
     * @param string $singular
     */
    private function fill_post_messages(string $singular): void
    {
        $this->fill_post_message(1, $singular);
        $this->fill_post_message(2, $singular);
        $this->fill_post_message(3, $singular);
        $this->fill_post_message(4, $singular);
        $this->fill_post_message(5, $singular);
        $this->fill_post_message(6, $singular);
        $this->fill_post_message(7, $singular);
        $this->fill_post_message(8, $singular);
        $this->fill_post_message(9, $singular);
        $this->fill_post_message(10, $singular);
    }

    /**
     * Fill post message (if not already set)
     *
     * @param int $index
     * @param string $noun
     */
    private function fill_post_message(int $index, string $noun): void
    {
        if (isset($this->post_messages[$index])) {
            return;
        }
        $message = sprintf($this->get_message_format($index), $noun);
        $this->add_post_message(
            $index, _x($message, 'Post Type: ' . $this->post_type, 'charm')
        );
    }

    /**
     * Get message format
     *
     * @param int $index
     * @return string
     */
    private function get_message_format(int $index): string
    {
        $formats = [
            1 => '%s updated.',
            2 => 'Custom field updated.',
            3 => 'Custom field deleted.',
            4 => '%s updated.',
            5 => '%s restored to revision.',
            6 => '%s published.',
            7 => '%s saved.',
            8 => '%s submitted.',
            9 => '%s scheduled.',
            10 => '%s draft updated.',
        ];
        if (!isset($formats[$index])) {
            return 'No message defined.';
        }

        return $formats[$index];
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get post messages
     *
     * @return array
     */
    public function get_post_messages(): array
    {
        return $this->post_messages;
    }

    /**
     * Set post messages
     *
     * @param array $post_messages
     */
    public function set_post_messages(array $post_messages)
    {
        $this->post_messages = $post_messages;
    }

    /**
     * Add post message
     *
     * @param int $key
     * @param string $message
     */
    public function add_post_message(int $key, string $message)
    {
        $this->post_messages[$key] = $message;
    }
}