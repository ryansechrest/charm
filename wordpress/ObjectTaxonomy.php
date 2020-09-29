<?php

namespace Charm\WordPress;

/**
 * Class ObjectTaxonomy
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress
 */
class ObjectTaxonomy
{
    /************************************************************************************/
    // Properties

    /**
     * Object ID
     *
     * @var int
     */
    protected $object_id = 0;

    /**
     * Taxonomy
     *
     * @var string
     */
    protected $taxonomy = '';

    /************************************************************************************/
    // Default constructor and load method

    /**
     * ObjectTerm constructor
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
        if (isset($data['object_id'])) {
            $this->object_id = (int) $data['object_id'];
        }
        if (isset($data['taxonomy'])) {
            $this->taxonomy = $data['taxonomy'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize object taxonomy
     *
     * @param int $object_id
     * @param string $taxonomy
     * @return static
     */
    public static function init(int $object_id, string $taxonomy): ?ObjectTaxonomy
    {
        return new static([
            'object_id' => $object_id,
            'taxonomy' => $taxonomy,
        ]);
    }

    /************************************************************************************/
    // Action methods

    /**
     * Get terms from object
     *
     * @see wp_get_object_terms()
     * @param array $args
     * @return array
     */
    public function get($args = []): array
    {
        $terms = wp_get_object_terms($this->object_id, $this->taxonomy, $args);
        if (!is_array($terms)) {
            return [];
        }

        return $terms;
    }

    /**
     * Does object have terms?
     *
     * @see is_object_in_term()
     * @param array $terms
     * @return bool
     */
    public function has(array $terms = []): bool
    {
        if (is_object_in_term($this->object_id, $this->taxonomy, $terms) !== true) {
            return false;
        }

        return true;
    }

    /**
     * Add terms to object
     *
     * @see wp_add_object_terms()
     * @param array $terms
     * @return int[]
     */
    public function add(array $terms): array
    {
        $term_ids = wp_add_object_terms($this->object_id, $terms, $this->taxonomy);
        if (!is_array($term_ids)) {
            return [];
        }

        return $term_ids;
    }

    /**
     * Set terms on object
     *
     * @see wp_set_object_terms()
     * @param array $terms
     * @return int[]
     */
    public function set(array $terms): array
    {
        $term_ids = wp_set_object_terms($this->object_id, $terms, $this->taxonomy);
        if (!is_array($term_ids)) {
            return [];
        }

        return $term_ids;
    }

    /**
     * Remove terms from object
     *
     * @see wp_remove_object_terms()
     * @param array $terms
     * @return bool
     */
    public function remove(array $terms): bool
    {
        if (wp_remove_object_terms($this->object_id, $terms, $this->taxonomy) !== true) {
            return false;
        }

        return true;
    }

    /**
     * Remove all terms from object
     *
     * @see wp_delete_object_term_relationships()
     * @return bool
     */
    public function purge(): bool
    {
        wp_delete_object_term_relationships($this->object_id, $this->taxonomy);

        return true;
    }

    /************************************************************************************/
    // Get and set methods

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
    public function set_taxonomy(string $taxonomy): void
    {
        $this->taxonomy = $taxonomy;
    }
}