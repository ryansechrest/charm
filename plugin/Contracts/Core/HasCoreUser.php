<?php

namespace Charm\Contracts\Proxy;

use Charm\Models\Proxy;

/**
 * Ensures that the model implements a `WordPress\User` instance.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasProxyUser
{
    /**
     * Provides access to the `WordPress\User` instance.
     *
     * @return ?Proxy\User
     */
    public function proxyUser(): ?Proxy\User;
}