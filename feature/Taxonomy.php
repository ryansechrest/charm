<?php

namespace Charm\Feature;

use Charm\Entity\ObjectTaxonomy;
use Charm\Skeleton\Taxonomy as TaxonomySkeleton;

/**
 * Trait Taxonomy
 *
 * @author Ryan Sechrest
 * @package Charm\Feature
 */
trait Taxonomy
{
    /************************************************************************************/
    // Properties

    /**
     * Taxonomies
     *
     * @var array
     */
    protected $taxonomies = [];

    /************************************************************************************/
    // Object access methods

    /**
     * Get object taxonomy
     *
     * @param string $taxonomy
     * @return ObjectTaxonomy
     */
    public function taxonomy(string $taxonomy): ObjectTaxonomy
    {
        /** @var TaxonomySkeleton $taxonomy */
        $name = $taxonomy::name();
        if (isset($this->taxonomies[$name])) {
            return $this->taxonomies[$name];
        }
        $object_taxonomy = ObjectTaxonomy::init($this->id, $name);
        $object_taxonomy->set_term_class($taxonomy::TERM);

        return $this->taxonomies[$name] = $object_taxonomy;
    }
}