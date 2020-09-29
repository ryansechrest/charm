<?php

namespace Charm\Entity;

use Charm\Feature\Meta as MetaFeature;
use Charm\WordPress\Term as WpTerm;

/**
* Class Term
*
* @author Ryan Sechrest
* @package Charm\Entity
*/
class Term extends WpTerm
{
    use MetaFeature;

    /************************************************************************************/
    // Constants

    /**
     * Meta class
     *
     * @var string
     */
    const META = 'Charm\Entity\TermMeta';

    /************************************************************************************/
    // Properties

    /**
     * Archive URL
     *
     * @var string
     */
    protected $archive_url = '';

    /*----------------------------------------------------------------------------------*/

    /**
     * Parent object
     *
     * @var Term|null
     */
    protected $parent_obj = null;

    /**
     * Child objects
     *
     * @var Term[]
     */
    protected $child_objs = [];

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        $data['taxonomy'] = static::taxonomy();
        parent::load($data);
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Get terms
     *
     * @param array $params
     * @return static[]
     */
    public static function get(array $params = []): array
    {
        $params['taxonomy'] = static::taxonomy();

        return parent::get($params);
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get taxonomy
     *
     * @return string
     */
    public static function taxonomy(): string
    {
        return '';
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get parent
     *
     * @return Term|null
     */
    public function parent(): ?Term
    {
        if ($this->parent_obj) {
            return $this->parent_obj;
        }
        if (!$this->parent) {
            return null;
        }

        return $this->parent_obj = static::init($this->parent);
    }

    /**
     * Get children
     *
     * @return Term[]
     */
    public function children(): array
    {
        if ($this->child_objs) {
            return $this->child_objs;
        }
        $children = get_ancestors($this->term_id, $this->taxonomy, 'taxonomy');
        if (count($children) === 0) {
            return [];
        }

        return $this->child_objs = array_map(function($term_id) {
            return static::init($term_id);
        }, $children);
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get archive URL
     *
     * @see get_term_link()
     * @return string
     */
    public function get_archive_url(): string
    {
        if ($this->archive_url !== '') {
            return $this->archive_url;
        }
        if ($this->term_id === 0) {
            return '';
        }
        $archive_url= get_term_link($this->term_id, $this->taxonomy);
        if (!is_string($archive_url)) {
            return '';
        }

        return $this->archive_url = $archive_url;
    }
}