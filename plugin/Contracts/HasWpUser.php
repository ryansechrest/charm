<?php

namespace Charm\Contracts;

use Charm\Models\WordPress\User;

/**
 * Ensures that the model has a WordPress user.
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
