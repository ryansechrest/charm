<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasProxyPost;

/**
 * Adds slug to post model.
 *
 * Table: wp_posts
 * Column: post_name
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithSlug
{
    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug(): string
    {
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getPostName();
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return static
     */
    public function setSlug(string $slug): static
    {
        /** @var HasProxyPost $this */
        $this->proxyPost()->setPostName($slug);

        return $this;
    }
}