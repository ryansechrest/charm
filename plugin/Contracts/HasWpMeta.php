<?php

namespace Charm\Contracts;

use Charm\Models\WordPress\Meta;

/**
 * Ensures wp() exists to return WordPress\Meta.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasWpMeta
{
    /**
     * Get meta
     *
     * @return ?Meta
     */
    public function wp(): ?Meta;
}
