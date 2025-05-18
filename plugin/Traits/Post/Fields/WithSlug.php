<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Proxy\HasProxyPost;

/**
 * Adds the slug to a post model.
 *
 * Table: `wp_posts`
 * Column: `post_name`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithSlug
{
    /**
     * Get the post's slug.
     *
     * @return string
     */
    public function getSlug(): string
    {
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getPostName();
    }

    /**
     * Set the post's slug.
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