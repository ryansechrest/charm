<?php

namespace Charm\Skeleton;

use Charm\Module\Taxonomy as TaxonomyModule;

/**
 * Class Taxonomy
 *
 * @author Ryan Sechrest
 * @package Charm\Skeleton
 */
abstract class Taxonomy
{
    /************************************************************************************/
    // Action methods

    /**
     * Register
     */
    public static function register(): void
    {
        static::taxonomy()->register();
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Taxonomy
     *
     * @return TaxonomyModule
     */
    abstract public static function taxonomy(): TaxonomyModule;
}