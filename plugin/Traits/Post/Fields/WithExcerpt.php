<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Proxy\HasProxyPost;

/**
 * Adds the excerpt to a post model.
 *
 * Table: `wp_posts`
 * Column: `post_excerpt`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithExcerpt
{
    /**
     * Get the post's excerpt.
     *
     * @return string
     */
    public function getExcerpt(): string
    {
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getPostExcerpt();
    }

    /**
     * Set the post's excerpt.
     *
     * @param string $excerpt
     * @return static
     */
    public function setExcerpt(string $excerpt): static
    {
        /** @var HasProxyPost $this */
        $this->proxyPost()->setPostExcerpt($excerpt);

        return $this;
    }
}