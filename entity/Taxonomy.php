<?php

namespace Charm\Entity;

use Charm\Entity\Term as Term;
use Charm\WordPress\Taxonomy as WpTaxonomy;

/**
 * Class Taxonomy
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class Taxonomy extends WpTaxonomy
{
    /************************************************************************************/
    // Constants

    /**
     * Term class
     *
     * @var string
     */
    const TERM = 'Charm\Entity\Term';

    /************************************************************************************/
    // Properties

    /**
     * Taxonomy terms
     *
     * @var array
     */
    protected $terms = [];

    /************************************************************************************/
    // Get and set methods

    /**
     * Get terms
     *
     * @param array $params
     * @return Term|Term[]
     */
    public function get_terms($params = [])
    {
        if (!isset($params['taxonomy'])) {
            $params['taxonomy'] = $this->name;
        }
        return call_user_func(
            static::TERM . '::get', $params
        );
    }
}