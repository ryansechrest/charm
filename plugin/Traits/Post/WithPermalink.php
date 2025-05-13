<?php

namespace Charm\Traits\Post;

use Charm\Contracts\WordPress\HasWpPost;

/**
 * Adds permalink to post model.
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