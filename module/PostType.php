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
    /************************************************************************************/
    // Action methods

    /**
     * Initialize properties to WordPress defaults
     */
    public function initialize_properties(): void
    {
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
            $this->rest_base = $this->name;
        }
        if ($this->rest_controller_class === '') {
            $this->rest_controller_class = 'WP_REST_Posts_Controller';
        }
        if ($this->menu_icon === '') {
            $this->menu_icon = 'dashicons-text-page';
        }
        if (count($this->capability_type) === 0) {
            $this->capability_type = ['post', 'posts'];
        }
        if ($this->map_meta_cap === null) {
            $this->map_meta_cap = true;
        }
        if (count($this->supports) === 0) {
            $this->supports = ['title', 'editor'];
        }
        if ($this->has_archive === '') {
            $this->has_archive = false;
        }
        if (count($this->rewrite) === 0) {
            $this->rewrite = [
                'slug' => $this->name,
                'with_front' => true,
                'feeds' => $this->has_archive,
                'pages' => true,
                'ep_mask' => EP_PERMALINK,
            ];
        }
        if ($this->query_var === '') {
            $this->query_var = $this->name;
        }
        if ($this->can_export === null) {
            $this->can_export = true;
        }
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Autogenerate everything
     *
     * @param array $methods
     */
    public function autogenerate(array $methods = []): void
    {
        if (count($methods) === 0) {
            $this->initialize_properties();
            $this->autogenerate_labels();
            $this->autogenerate_capabilities();
            $this->autogenerate_post_updated_messages();
            return;
        }
        foreach ($methods as $method) {
            $method = 'autogenerate_' . $method;
            if (!method_exists($this, $method)) {
                continue;
            }
            $this->$method();
        }
    }

    /**
     * Autogenerate labels
     */
    public function autogenerate_labels(): void
    {
        $singular = $this->get_singular_label();
        $plural = $this->get_plural_label();
        if ($this->label === '') {
            $this->label = $plural;
        }
        $this->fill_labels($singular, $plural);
    }

    /**
     * Autogenerate capabilities
     */
    public function autogenerate_capabilities(): void
    {
        $singular = $this->name;
        $plural = $singular . 's';
        if (isset($this->capability_type[0])) {
            $singular = $this->capability_type[0];
            $plural = $singular . 's';
        }
        if (isset($this->capability_type[1])) {
            $plural = $this->capability_type[1];
        }
        if (count($this->capability_type) === 0) {
            $this->capability_type = [$singular, $plural];
        }
        $this->fill_capabilities($singular, $plural);
    }

    /**
     * Autogenerate post updated messages
     */
    public function autogenerate_post_updated_messages(): void
    {
        $this->fill_post_updated_messages($this->get_singular_label());
    }

    /************************************************************************************/
    // Private label methods

    /**
     * Get singular label
     *
     * @return string
     */
    private function get_singular_label()
    {
        if (isset($this->labels['singular_name'])) {
            return $this->labels['singular_name'];
        }

        return ucwords(str_replace('_', ' ', $this->name));
    }

    /**
     * Get plural label
     *
     * @return string
     */
    private function get_plural_label()
    {
        if (isset($this->labels['name'])) {
            return $this->labels['name'];
        }
        if ($this->label !== '') {
            return $this->label;
        }

        return $this->get_singular_label() . 's';
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
            $key, _x($label, 'Post Type: ' . $this->name, 'charm')
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
            return 'Label format not found.';
        }

        return $formats[$name];
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Fill capabilities into $this->capabilities
     *
     * @param string $singular
     * @param string $plural
     */
    public function fill_capabilities(string $singular, string $plural): void
    {
        // Meta capabilities
        $this->fill_capability('edit_post', 'edit_' . $singular);
        $this->fill_capability('read_post', 'read_' . $singular);
        $this->fill_capability('delete_post', 'delete_' . $singular);
        // Primitive capabilities used outside of map_meta_cap()
        $this->fill_capability('edit_posts', 'edit_' . $plural);
        $this->fill_capability('edit_others_posts', 'edit_others_' . $plural);
        $this->fill_capability('publish_posts', 'publish_' . $plural);
        $this->fill_capability('read_private_posts', 'read_private_' . $plural);
        // Primitive capabilities used within map_meta_cap()
        $this->fill_capability('read', 'read');
        $this->fill_capability('delete_posts', 'delete_' . $plural);
        $this->fill_capability('delete_private_posts', 'delete_private_' . $plural);
        $this->fill_capability('delete_published_posts', 'delete_published_' . $plural);
        $this->fill_capability('delete_others_posts', 'delete_others_' . $plural);
        $this->fill_capability('edit_private_posts', 'edit_private_' . $plural);
        $this->fill_capability('edit_published_posts', 'edit_published_' . $plural);
    }

    /**
     * Fill capability (if not already set)
     *
     * @param string $key
     * @param string $capability
     */
    public function fill_capability(string $key, string $capability): void
    {
        if (isset($this->capabilities[$key])) {
            return;
        }
        $this->add_capability($key, $capability);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Fill post updated messages into $this->post_updated_messages
     *
     * @param string $singular
     */
    private function fill_post_updated_messages(string $singular): void
    {
        $this->fill_post_updated_message(1, $singular);
        $this->fill_post_updated_message(2, $singular);
        $this->fill_post_updated_message(3, $singular);
        $this->fill_post_updated_message(4, $singular);
        $this->fill_post_updated_message(5, $singular);
        $this->fill_post_updated_message(6, $singular);
        $this->fill_post_updated_message(7, $singular);
        $this->fill_post_updated_message(8, $singular);
        $this->fill_post_updated_message(9, $singular);
        $this->fill_post_updated_message(10, $singular);
    }

    /**
     * Fill post updated message (if not already set)
     *
     * @param int $index
     * @param string $noun
     */
    private function fill_post_updated_message(int $index, string $noun): void
    {
        if (isset($this->post_updated_messages[$index])) {
            return;
        }
        $message = sprintf($this->get_post_updated_message_format($index), $noun);
        $this->add_post_updated_message(
            $index, _x($message, 'Post Type: ' . $this->name, 'charm')
        );
    }

    /**
     * Get post updated message format
     *
     * @param int $index
     * @return string
     */
    private function get_post_updated_message_format(int $index): string
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
            return 'Message format not found.';
        }

        return $formats[$index];
    }
}