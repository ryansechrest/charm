<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Proxy\HasProxyPost;

/**
 * Adds title to post model.
 *
 * Table: wp_posts
 * Column: post_title
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithTitle
{
    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getPostTitle();
    }

    /**
     * Set title
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