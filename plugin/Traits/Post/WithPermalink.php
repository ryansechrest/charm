<?php

namespace Charm\Traits\Post;

use Charm\Contracts\WordPress\HasWpPost;

/**
 * Adds the permalink to a post model.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithPermalink
{
    /**
     * Get the post's permalink.
     *
     * @return string
     * @see get_permalink()
     */
    public function getPermalink(): string
    {
        /** @var HasWpPost $this */
        return get_permalink($this->corePost()->getId());
    }
}