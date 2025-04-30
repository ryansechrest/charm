<?php

namespace Charm\Contracts;

use Charm\Models\WordPress\Meta;

/**
 * Ensures that the model has a WordPress meta.
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
