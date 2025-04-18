<?php

namespace Charm\Contracts;

use Charm\Models\WordPress\Post;

/**
 * Ensures wp() exists and returns WordPress\Post.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasWpPost
{
    /**
     * Get post
     *
     * @return ?Post
     */
    public function wp(): ?Post;
}
