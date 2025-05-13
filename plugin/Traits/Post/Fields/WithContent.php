<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Proxy\HasProxyPost;

/**
 * Adds content to post model.
 *
 * Table: wp_posts
 * Column: post_content
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithContent
{
    /**
     * Get content
     *
     * @return string
     */
    public function getContent(): string
    {
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getPostContent();
    }

    /**
     * Set content
     *
     * @param string $content
     * @return static
     */
    public function setContent(string $content): static
    {
        /** @var HasProxyPost $this */
        $this->proxyPost()->setPostContent($content);

        return $this;
    }
}