<?php

namespace Charm\Contracts;

use Charm\Models\Proxy;

/**
 * Ensures that the model implements a WordPress\User instance.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasProxyUser
{
    /**
     * Provides access to WordPress\User instance.
     *
     * @return ?Proxy\User
     */
    public function proxyUser(): ?Proxy\User;
}