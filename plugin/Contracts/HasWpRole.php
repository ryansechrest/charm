<?php

namespace Charm\Contracts;

use Charm\Structures\WordPress\Role;

/**
 * Ensures that the model has a WordPress role.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasWpRole
{
    /**
     * Get role
     *
     * @return ?Role
     */
    public function wp(): ?Role;
}
