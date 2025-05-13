<?php

namespace Charm\Contracts;

use Charm\Models\Proxy;

/**
 * Ensures that the model implements a WordPress\Term instance.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasProxyTerm
{
    /**
     * Provides access to WordPress\Term instance.
     *
     * @return ?Proxy\Term
     */
    public function proxyTerm(): ?Proxy\Term;
}