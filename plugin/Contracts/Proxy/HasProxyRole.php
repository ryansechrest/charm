<?php

namespace Charm\Contracts\Proxy;

use Charm\Models\Proxy;

/**
 * Ensures that the model implements a `Proxy\Role` instance.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasProxyRole
{
    /**
     * Provides access to the `Proxy\Role` instance.
     *
     * @return ?Proxy\Role
     */
    public function proxyRole(): ?Proxy\Role;
}