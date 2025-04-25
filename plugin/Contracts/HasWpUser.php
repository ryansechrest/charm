<?php

namespace Charm\Contracts;

use Charm\Models\WordPress\User;

/**
 * Ensures wp() exists to return WordPress\User.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasWpUser
{
    /**
     * Get user
     *
     * @return ?User
     */
    public function wp(): ?User;
}
