<?php

namespace Charm\Contracts;

use Charm\Models\WordPress\Post;

/**
 * Ensures that the model has a WordPress post.
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
