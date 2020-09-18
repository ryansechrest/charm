<?php

namespace Charm\WordPress;

use WP_Taxonomy;

/**
 * Class Taxonomy
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress
 */
class Taxonomy
{
    /************************************************************************************/
    // Properties

    /**
     * Name
     *
     * @var string
     */
    protected $name = '';

    /**
     * Label
     *
     * @var string
     */
    protected $label = '';

    /**
     * Description
     *
     * @var string
     */
    protected $description = '';

    /*----------------------------------------------------------------------------------*/

    /**
     * WordPress taxonomy
     *
     * @var WP_Taxonomy
     */
    private $wp_taxonomy = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Post constructor
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
        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize taxonomy
     *
     * @see WP_Taxonomy
     * @param string|WP_Taxonomy $key
     * @return static|null
     */
    public static function init($key): ?Taxonomy
    {
        $taxonomy = new static();
        if (is_string($key)) {
            $taxonomy->load_from_name($key);
        } elseif (is_object($key) && get_class($key) === 'WP_Taxonomy') {
            $taxonomy->load_from_taxonomy($key);
        }
        if ($taxonomy->name === '') {
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
        $taxonomies = get_taxonomies($params, 'objects');
        if (!is_array($taxonomies)) {
            return [];
        }

        return array_map(function(WP_Taxonomy $taxonomy) {
            return static::init($taxonomy);
        }, $taxonomies);
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
        if (!$taxonomy = get_taxonomy($name)) {
            return;
        }
        $this->load_from_taxonomy($taxonomy);
    }

    /**
     * Load instance from WP_Taxonomy object
     *
     * @see WP_Taxonomy
     * @param WP_Taxonomy $taxonomy
     */
    protected function load_from_taxonomy(WP_Taxonomy $taxonomy): void
    {
        $this->name = $taxonomy->name;
        $this->label = $taxonomy->label;
        $this->description = $taxonomy->description;
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
    // Cast methods

    /**
     * Cast instance to array
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
        if ($this->description !== '') {
            $data['description'] = $this->description;
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
}