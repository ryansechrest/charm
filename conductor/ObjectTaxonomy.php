<?php

namespace Charm\Conductor;

use Charm\Entity\Term;
use Charm\WordPress\Conductor\ObjectTaxonomy as WpObjectTaxonomy;

/**
 * Class ObjectTaxonomy
 *
 * @author Ryan Sechrest
 * @package Charm\Conductor
 */
class ObjectTaxonomy extends WpObjectTaxonomy
{
    /************************************************************************************/
    // Properties

    /**
     * Term class
     *
     * @var string
     */
    protected $term_class = '';

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        parent::load($data);
        if (isset($data['term_class'])) {
            $this->term_class = $data['term_class'];
        }
    }

    /************************************************************************************/
    // Action methods

    /**
     * Get terms from object
     *
     * @param array $args
     * @return Term[]
     */
    public function get($args = []): array
    {
        return array_map(function($term) {
            return call_user_func(
                $this->term_class . '::init', $term
            );
        }, parent::get($args));
    }

    /**
     * Add terms to object
     *
     * @param array $terms
     * @return Term[]
     */
    public function add(array $terms): array
    {
        return array_map(function($term) {
            return call_user_func(
                $this->term_class . '::init', $term
            );
        }, parent::add($terms));
    }

    /**
     * Set terms on object
     *
     * @param array $terms
     * @return Term[]
     */
    public function set(array $terms): array
    {
        return array_map(function($term) {
            return call_user_func(
                $this->term_class . '::init', $term
            );
        }, parent::set($terms));
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get term class
     *
     * @return string
     */
    public function get_term_class(): string
    {
        return $this->term_class;
    }

    /**
     * Set term class
     *
     * @param string $term_class
     */
    public function set_term_class(string $term_class): void
    {
        $this->term_class = $term_class;
    }


}