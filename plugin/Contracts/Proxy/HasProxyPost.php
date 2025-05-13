<?php

namespace Charm\Contracts\Proxy;

use Charm\Models\Proxy;

/**
 * Ensures that the model implements a WordPress\Post instance.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasProxyPost
{
    /**
     * Provides access to WordPress\Post instance.
     *
     * @return ?Proxy\Post
     */
    public function proxyPost(): ?Proxy\Post;
}