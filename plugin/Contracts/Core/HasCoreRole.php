<?php

namespace Charm\Contracts\Core;

use Charm\Models\Core;

/**
 * Ensures that the model implements a `Core\Role` instance.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasCoreRole
{
    /**
     * Provides access to the `Core\Role` instance.
     *
     * @return ?Core\Role
     */
    public function coreRole(): ?Core\Role;
}