<?php

namespace Charm\Contracts\Proxy;

use Charm\Models\Proxy;

/**
 * Ensures that the model implements a WordPress\Meta instance.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasProxyMeta
{
    /**
     * Provides access to WordPress\Meta instance.
     *
     * @return ?Proxy\Meta
     */
    public function proxyMeta(): ?Proxy\Meta;
}