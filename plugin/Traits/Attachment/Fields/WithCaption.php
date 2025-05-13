<?php

namespace Charm\Traits\Attachment\Fields;

use Charm\Contracts\HasProxyPost;

/**
 * Adds caption to attachment model.
 *
 * Table: wp_posts
 * Column: post_excerpt
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithCaption
{
    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption(): string
    {
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getPostExcerpt();
    }

    /**
     * Set caption
     *
     * @param string $caption
     * @return static
     */
    public function setCaption(string $caption): static
    {
        /** @var HasProxyPost $this */
        $this->proxyPost()->setPostExcerpt($caption);

        return $this;
    }
}