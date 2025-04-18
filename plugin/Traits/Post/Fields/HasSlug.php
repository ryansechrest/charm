<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;

/**
 * Indicates that a post has a slug.
 *
 * Table: wp_posts
 * Column: post_name
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasSlug
{
    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug(): string
    {
        /** @var HasWpPost $this */
        return $this->wp()->getPostName();
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return static
     */
    public function setSlug(string $slug): static
    {
        /** @var HasWpPost $this */
        $this->wp()->setPostName($slug);

        return $this;
    }
}