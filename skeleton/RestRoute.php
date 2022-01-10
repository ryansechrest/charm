<?php

namespace Charm\Skeleton;

use Charm\Module\Rest\Route as RestRouteModule;

/**
 * Class RestRoute
 *
 * @author Ryan Sechrest
 * @package Charm\Skeleton
 */
abstract class RestRoute
{
    /************************************************************************************/
    // Action methods

    /**
     * Register menu location
     */
    public static function register(): void
    {
        foreach (static::rest_routes() as $route) {
            $route->register();
        }
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get REST route
     *
     * @return RestRouteModule[]
     */
    public static function rest_routes(): array
    {
        return [];
    }
}