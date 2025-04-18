<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;

/**
 * Indicates that a post has an excerpt.
 *
 * Table: wp_posts
 * Column: post_excerpt
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasExcerpt
{
    /**
     * Get excerpt
     *
     * @return string
     */
    public function getExcerpt(): string
    {
        /** @var HasWpPost $this */
        return $this->wp()->getPostExcerpt();
    }

    /**
     * Set excerpt
     *
     * @param string $excerpt
     * @return static
     */
    public function setExcerpt(string $excerpt): static
    {
        /** @var HasWpPost $this */
        $this->wp()->setPostExcerpt($excerpt);

        return $this;
    }
}