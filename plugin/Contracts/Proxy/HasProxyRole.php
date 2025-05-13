<?php

namespace Charm\Contracts\Proxy;

use Charm\Structures\Proxy;

/**
 * Ensures that the model implements a Proxy\Role instance.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasProxyRole
{
    /**
     * Provides access to Proxy\Role instance.
     *
     * @return ?Proxy\Role
     */
    public function proxyRole(): ?Proxy\Role;
}