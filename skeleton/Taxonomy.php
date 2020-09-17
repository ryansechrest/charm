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

    /*----------------------------------------------------------------------------------*/

    /**
     * Get name
     *
     * @return string
     */
    public static function name(): string
    {
        return static::taxonomy()->get_name();
    }

    /**
     * Get capabilities
     *
     * @param array $target_capabilities
     * @return array
     */
    public static function capabilities($target_capabilities = []): array
    {
        $all_capabilities = static::taxonomy()->get_capabilities();
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
}