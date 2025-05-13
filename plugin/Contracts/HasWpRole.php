<?php

namespace Charm\Contracts;

use WP_Role;

/**
 * Ensures that the model implements a WP_Role instance.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasWpRole
{
    /**
     * Provides access to WP_Role instance.
     *
     * @return ?WP_Role
     */
    public function wpRole(): ?WP_Role;
}