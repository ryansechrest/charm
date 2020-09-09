<?php

namespace Charm\Skeleton;

use Charm\Module\PostType as PostTypeModule;
use Charm\Module\Taxonomy;

/**
 * Class PostType
 *
 * @author Ryan Sechrest
 * @package Charm\Skeleton
 */
abstract class PostType
{
    /************************************************************************************/
    // Action methods

    /**
     * Register post type
     */
    public static function register(): void
    {
        static::post_type()->register();
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get post type
     *
     * @return PostTypeModule
     */
    abstract public static function post_type(): PostTypeModule;

    /*----------------------------------------------------------------------------------*/

    /**
     * Get taxonomies
     *
     * @return Taxonomy[]
     */
    public static function taxonomies(): array
    {
        return static::post_type()->taxonomies();
    }
}