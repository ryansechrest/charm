<?php

namespace Charm\Contracts\Core;

use Charm\Models\Core;

/**
 * Ensures that the model implements a `Core\User` instance.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasCoreUser
{
    /**
     * Provides access to the `Core\User` instance.
     *
     * @return ?Core\User
     */
    public function coreUser(): ?Core\User;
}