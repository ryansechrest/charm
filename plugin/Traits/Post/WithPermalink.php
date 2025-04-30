<?php

namespace Charm\Traits\Post;

use Charm\Contracts\HasWpPost;

/**
 * Indicates that a post has a permalink.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithPermalink
{
    /**
     * Get permalink
     *
     * @return string
     * @see get_permalink()
     */
    public function getPermalink(): string
    {
        /** @var HasWpPost $this */
        return get_permalink($this->wp()->getId());
    }
}