<?php

namespace Charm\Contracts\WordPress;

use WP_User;

/**
 * Ensures that the model implements a WP_User instance.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasWpUser
{
    /**
     * Provides access to WP_User instance.
     *
     * @return ?WP_User
     */
    public function wpUser(): ?WP_User;
}