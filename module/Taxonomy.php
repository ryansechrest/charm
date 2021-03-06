<?php

namespace Charm\Module;

use Charm\WordPress\Module\Taxonomy as WpTaxonomy;

/**
 * Class Taxonomy
 *
 * @author Ryan Sechrest
 * @package Charm\Module
 */
class Taxonomy extends WpTaxonomy
{
    /************************************************************************************/
    // Action methods

    /**
     * Initialize properties to WordPress defaults
     */
    public function initialize_properties(): void
    {
        if ($this->object_type === '') {
            $this->object_type = null;
        }
        if ($this->public === null) {
            $this->public = true;
        }
        if ($this->publicly_queryable === null) {
            $this->publicly_queryable = $this->public;
        }
        if ($this->show_ui === null) {
            $this->show_ui = $this->public;
        }
        if ($this->show_in_menu === null) {
            $this->show_in_menu = $this->show_ui;
        }
        if ($this->show_in_nav_menus === null) {
            $this->show_in_nav_menus = $this->public;
        }
        if ($this->show_in_rest === null) {
            $this->show_in_rest = false;
        }
        if ($this->rest_base === '') {
            $this->rest_base = $this->name;
        }
        if ($this->rest_controller_class === '') {
            $this->rest_controller_class = 'WP_REST_Terms_Controller';
        }
        if ($this->show_tagcloud === null) {
            $this->show_tagcloud = $this->show_ui;
        }
        if ($this->show_in_quick_edit === null) {
            $this->show_in_quick_edit = $this->show_ui;
        }
        if ($this->meta_box_cb === null) {
            $this->meta_box_cb = 'post_tags_meta_box';
            if ($this->hierarchical) {
                $this->meta_box_cb = 'post_categories_meta_box';
            }
        }
        if ($this->meta_box_sanitize_cb === null) {
            $this->meta_box_sanitize_cb = 'taxonomy_meta_box_sanitize_cb_input';
            if ($this->hierarchical) {
                $this->meta_box_sanitize_cb = 'taxonomy_meta_box_sanitize_cb_checkboxes';
            }
        }
        if ($this->show_admin_column === null) {
            $this->show_admin_column = false;
        }
        if ($this->hierarchical === null) {
            $this->hierarchical = false;
        }
        if ($this->query_var === '') {
            $this->query_var = $this->name;
        }
        if (count($this->rewrite) === 0) {
            $this->rewrite = [
                'slug' => $this->name,
                'with_front' => true,
                'hierarchical' => false,
                'ep_mask' => EP_NONE,
            ];
        }
        if ($this->capabilities === null) {
            $this->capabilities = [
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'edit_posts',
            ];
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
        if (!is_array($this->capabilities)) {
            return;
        }
        $this->fill_capabilities($this->name);
    }

    /************************************************************************************/
    // Private label methods

    /**
     * Get singular label
     *
     * @return string
     */
    private function get_singular_label(): string
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
    private function get_plural_label(): string
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
        $this->fill_label('search_items', $plural);
        $this->fill_label('popular_items', $plural);
        $this->fill_label('all_items', $plural);
        $this->fill_label('parent_item', $singular);
        $this->fill_label('parent_item_colon', $singular);
        $this->fill_label('edit_item', $singular);
        $this->fill_label('view_item', $singular);
        $this->fill_label('update_item', $singular);
        $this->fill_label('add_new_item', $singular);
        $this->fill_label('new_item_name', $singular);
        $this->fill_label('separate_items_with_commas', strtolower($plural));
        $this->fill_label('add_or_remove_items', strtolower($plural));
        $this->fill_label('choose_from_most_used', strtolower($plural));
        $this->fill_label('not_found', strtolower($plural));
        $this->fill_label('no_terms', strtolower($plural));
        $this->fill_label('items_list_navigation', $plural);
        $this->fill_label('items_list', $plural);
        $this->fill_label('most_used', $plural);
        $this->fill_label('back_to_items', strtolower($plural));
        $this->fill_label('menu_name', $plural);
        $this->fill_label('name_admin_bar', $plural);
        $this->fill_label('archives', $plural);
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
            $key, _x($label, 'Taxonomy: ' . $this->name, 'charm')
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
            'search_items' => 'Search %s',
            'popular_items' => 'Popular %s',
            'all_items' => 'All %s',
            'parent_item' => 'Parent %s',
            'parent_item_colon' => 'Parent %s:',
            'edit_item' => 'Edit %s',
            'view_item' => 'View %s',
            'update_item' => 'Update %s',
            'add_new_item' => 'Add New %s',
            'new_item_name' => 'New %s Name',
            'separate_items_with_commas' => 'Separate %s with commas',
            'add_or_remove_items' => 'Add or remove %s',
            'choose_from_most_used' => 'Choose from popular %s',
            'not_found' => 'No %s found.',
            'no_terms' => 'No %s',
            'items_list_navigation' => '%s list navigation',
            'items_list' => '%s list',
            'most_used' => 'Popular %s',
            'back_to_items' => '← Back to %s',
            'menu_name' => '%s',
            'name_admin_bar' => '%s',
            'archives' => 'All %s',
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
     * @param string $name
     */
    public function fill_capabilities(string $name): void
    {
        $this->fill_capability('manage_terms', 'manage_' . $name);
        $this->fill_capability('edit_terms', 'edit_' . $name);
        $this->fill_capability('delete_terms', 'delete_' . $name);
        $this->fill_capability('assign_terms', 'assign_' . $name);
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
}