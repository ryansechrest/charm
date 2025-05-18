<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Proxy\HasProxyPost;

/**
 * Adds the title to a post model.
 *
 * Table: `wp_posts`
 * Column: `post_title`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithTitle
{
    /**
     * Get the post's title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getPostTitle();
    }

    /**
     * Set the post's title.
     *
     * @param string $title
     * @return static
     */
    public function setTitle(string $title): static
    {
        /** @var HasProxyPost $this */
        $this->proxyPost()->setPostTitle($title);

        return $this;
    }
}