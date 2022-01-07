<?php

namespace Charm\WordPress;

use WP_Term;

/**
 * Class Term
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress
 */
class Term
{
    /************************************************************************************/
    // Properties

    /**
     * Term ID
     *
     * @var int
     */
    protected int $term_id = 0;

    /**
     * Name
     *
     * @var string
     */
    protected string $name = '';

    /**
     * Slug
     *
     * @var string
     */
    protected string $slug = '';

    /**
     * Term group
     *
     * @var int
     */
    protected int $term_group = 0;

    /**
     * Term taxonomy ID
     *
     * @var int
     */
    protected int $term_taxonomy_id = 0;

    /**
     * Taxonomy
     *
     * @var string
     */
    protected string $taxonomy = '';

    /**
     * Description
     *
     * @var string
     */
    protected string $description = '';

    /**
     * Parent
     *
     * @var int
     */
    protected int $parent = 0;

    /**
     * Count
     *
     * @var int
     */
    protected int $count = 0;

    /**
     * Filter
     *
     * @var string
     */
    protected string $filter = '';

    /*----------------------------------------------------------------------------------*/

    /**
     * WordPress term
     *
     * @var WP_Term|null
     */
    private ?WP_Term $wp_term = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Term constructor
     *
     * Not doing an if (count($data)) check here like in other load methods so that
     * when a child class is instantiated, it will always call the overridden
     * load method of that child class, which sets the taxonomy.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['term_id'])) {
            $this->term_id = (int) $data['term_id'];
        }
        if (isset($data['name'])) {
            $this->name = $data['name'];
        }
        if (isset($data['slug'])) {
            $this->slug = $data['slug'];
        }
        if (isset($data['term_group'])) {
            $this->term_group = (int) $data['term_group'];
        }
        if (isset($data['term_taxonomy_id'])) {
            $this->term_taxonomy_id = (int) $data['term_taxonomy_id'];
        }
        if (isset($data['taxonomy'])) {
            $this->taxonomy = $data['taxonomy'];
        }
        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
        if (isset($data['parent'])) {
            $this->parent = (int) $data['parent'];
        }
        if (isset($data['count'])) {
            $this->count = (int) $data['count'];
        }
        if (isset($data['filter'])) {
            $this->filter = $data['filter'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize term
     *
     * @see WP_Term
     * @param WP_Term|int|string $key
     * @param string $taxonomy
     * @return static|null
     */
    public static function init(WP_Term|int|string $key, string $taxonomy = ''): ?Term
    {
        $term = new static();
        if (is_int($key) || ctype_digit($key)) {
            $term->load_from_id($key, $taxonomy);
        } elseif (is_string($key)) {
            $term->load_from_name_or_slug($key, $taxonomy);
        } elseif (is_object($key) && get_class($key) === 'WP_Term') {
            $term->load_from_term($key);
        }
        if ($term->get_term_id() === 0) {
            return null;
        }

        return $term;
    }

    /**
     * Get terms
     *
     * @see get_terms()
     * @param array $params
     * @return static[]
     */
    public static function get(array $params = []): array
    {
        $terms = get_terms($params);
        if (!is_array($terms)) {
            return [];
        }

        return array_map(function(WP_Term $term) {
            return static::init($term);
        }, $terms);
    }

    /************************************************************************************/
    // Protected load methods

    /**
     * Load instance from ID
     *
     * @see get_term()
     * @param int $term_id
     * @param string $taxonomy
     */
    protected function load_from_id(int $term_id, string $taxonomy = ''): void
    {
        $term = get_term($term_id, $taxonomy);
        if (get_class($term) !== 'WP_Term') {
            return;
        }
        $this->load_from_term($term);
    }

    /**
     * Load instance from name or slug
     *
     * @see term_exists()
     * @param string $name
     * @param string $taxonomy
     */
    protected function load_from_name_or_slug(string $name, string $taxonomy): void
    {
        $result = term_exists($name, $taxonomy);
        if ($result === null || $result === 0) {
            return;
        } elseif (is_int($result)) {
            $this->load_from_id($result);
        } elseif (is_array($result)) {
            $this->load_from_id($result['term_id']);
        }
    }

    /**
     * Load instance from WP_Term object
     *
     * @see WP_Term
     * @param WP_Term $term
     */
    protected function load_from_term(WP_Term $term): void
    {
        $this->term_id = (int) $term->term_id;
        $this->name = $term->name;
        $this->slug = $term->slug;
        $this->term_group = (int) $term->term_group;
        $this->term_taxonomy_id = (int) $term->term_taxonomy_id;
        $this->taxonomy = $term->taxonomy;
        $this->description = $term->description;
        $this->parent = (int) $term->parent;
        $this->count = (int) $term->count;
        $this->filter = $term->filter;
        $this->wp_term = $term;
    }

    /**
     * Reload instance from database
     */
    protected function reload(): void
    {
        if (!$this->term_id) {
            return;
        }
        $this->load_from_id($this->term_id);
    }

    /************************************************************************************/
    // Action methods

    /**
     * Save term
     *
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->term_id) {
            return $this->create();
        }

        return $this->update();
    }

    /**
     * Create new term (if it doesn't exist)
     *
     * @see wp_insert_term()
     * @return bool
     */
    public function create(): bool
    {
        $result = wp_insert_term($this->name, $this->taxonomy, [
            'alias_of' => '',
            'description' => $this->description,
            'parent' => $this->parent,
            'slug' => $this->slug,
        ]);
        if (!is_array($result)) {
            return false;
        }
        $this->term_id = $result['term_id'];
        $this->term_taxonomy_id = $result['term_taxonomy_id'];
        $this->reload();

        return true;
    }

    /**
     * Update existing term
     *
     * @see wp_update_term()
     * @return bool
     */
    public function update(): bool
    {
        $result = wp_update_term($this->term_id, $this->taxonomy, [
            'alias_of' => '',
            'description' => $this->description,
            'parent' => $this->parent,
            'slug' => $this->slug,
        ]);
        if (!is_array($result)) {
            return false;
        }
        $this->term_id = $result['term_id'];
        $this->term_taxonomy_id = $result['term_taxonomy_id'];
        $this->reload();

        return true;
    }

    /**
     * Delete term
     *
     * @see wp_delete_term()
     * @return bool
     */
    public function delete(): bool
    {
        if (wp_delete_term($this->term_id, $this->taxonomy) !== true) {
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
        if ($this->term_id !== 0) {
            $data['term_id'] = $this->term_id;
        }
        if ($this->name !== '') {
            $data['name'] = $this->name;
        }
        if ($this->slug !== '') {
            $data['slug'] = $this->slug;
        }
        if ($this->term_group !== 0) {
            $data['term_group'] = $this->term_group;
        }
        if ($this->term_taxonomy_id !== 0) {
            $data['term_taxonomy_id'] = $this->term_taxonomy_id;
        }
        if ($this->taxonomy !== '') {
            $data['taxonomy'] = $this->taxonomy;
        }
        if ($this->description !== '') {
            $data['description'] = $this->description;
        }
        if ($this->parent !== 0) {
            $data['parent'] = $this->parent;
        }
        if ($this->count !== 0) {
            $data['count'] = $this->count;
        }
        if ($this->filter !== '') {
            $data['filter'] = $this->filter;
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
     * Get (or set) WordPress term
     *
     * @param WP_Term|null $term
     * @return WP_Term
     */
    protected function wp_term(WP_Term $term = null): WP_Term
    {
        if ($term !== null) {
            $this->wp_term = $term;
        }

        return $this->wp_term;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get term ID
     *
     * @return int
     */
    public function get_term_id(): int
    {
        return $this->term_id;
    }

    /**
     * Set term ID
     *
     * @param int $term_id
     */
    public function set_term_id(int $term_id): void
    {
        $this->term_id = $term_id;
    }

    /*----------------------------------------------------------------------------------*/

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
    public function set_name(string $name)
    {
        $this->name = $name;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get slug
     *
     * @return string
     */
    public function get_slug(): string
    {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function set_slug(string $slug)
    {
        $this->slug = $slug;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get term group
     *
     * @return int
     */
    public function get_term_group(): int
    {
        return $this->term_group;
    }

    /**
     * Set term group
     *
     * @param int $term_group
     */
    public function set_term_group(int $term_group)
    {
        $this->term_group = $term_group;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get term taxonomy ID
     *
     * @return int
     */
    public function get_term_taxonomy_id(): int
    {
        return $this->term_taxonomy_id;
    }

    /**
     * Set term taxonomy ID
     *
     * @param int $term_taxonomy_id
     */
    public function set_term_taxonomy_id(int $term_taxonomy_id)
    {
        $this->term_taxonomy_id = $term_taxonomy_id;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get taxonomy
     *
     * @return string
     */
    public function get_taxonomy(): string
    {
        return $this->taxonomy;
    }

    /**
     * Set taxonomy
     *
     * @param string $taxonomy
     */
    public function set_taxonomy(string $taxonomy)
    {
        $this->taxonomy = $taxonomy;
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
    public function set_description(string $description)
    {
        $this->description = $description;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get parent
     *
     * @return int
     */
    public function get_parent(): int
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param int $parent
     */
    public function set_parent(int $parent)
    {
        $this->parent = $parent;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get count
     *
     * @return int
     */
    public function get_count(): int
    {
        return $this->count;
    }

    /**
     * Set count
     *
     * @param int $count
     */
    public function set_count(int $count)
    {
        $this->count = $count;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get filter
     *
     * @return string
     */
    public function get_filter(): string
    {
        return $this->filter;
    }

    /**
     * Set filter
     *
     * @param string $filter
     */
    public function set_filter(string $filter)
    {
        $this->filter = $filter;
    }
}