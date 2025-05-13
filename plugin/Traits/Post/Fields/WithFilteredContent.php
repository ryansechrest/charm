<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasProxyPost;

/**
 * Adds filtered content to post model.
 *
 * Table: wp_posts
 * Column: post_content_filtered
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithFilteredContent
{
    /**
     * Get filtered content
     *
     * @return string
     */
    public function getFilteredContent(): string
    {
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getPostContentFiltered();
    }

    /**
     * Set filtered content
     *
     * @param string $content
     * @return static
     */
    public function setFilteredContent(string $content): static
    {
        /** @var HasProxyPost $this */
        $this->proxyPost()->setPostContentFiltered($content);

        return $this;
    }
}