<?php

namespace Charm\Contracts\WordPress;

use WP_Role;

/**
 * Ensures that the model implements a `WP_Role` instance.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasWpRole
{
    /**
     * Provides access to the `WP_Role` instance.
     *
     * @return ?WP_Role
     */
    public function wpRole(): ?WP_Role;
}