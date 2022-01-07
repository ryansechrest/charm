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
     * Get name
     *
     * @return string
     */
    public static function name(): string
    {
        return static::post_type()->get_name();
    }

    /**
     * Get capabilities
     *
     * @param array $target_capabilities
     * @return array
     */
    public static function capabilities(array $target_capabilities = []): array
    {
        $all_capabilities = static::post_type()->get_capabilities();
        if (count($target_capabilities) === 0) {
            return array_values($all_capabilities);
        }
        $mapped_capabilities = [];
        foreach ($target_capabilities as $index => $target_capability) {
            if (!isset($all_capabilities[$target_capability])) {
                continue;
            }
            $mapped_capabilities[] = $all_capabilities[$target_capability];
        }

        return $mapped_capabilities;
    }

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