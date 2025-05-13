<?php

namespace Charm\Traits\Attachment\Fields;

use Charm\Contracts\Proxy\HasProxyPost;

/**
 * Adds MIME type to attachment model.
 *
 * Table: wp_posts
 * Column: post_mime_type
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithMimeType
{
    /**
     * Get MIME type
     *
     * @return string
     */
    public function getMimeType(): string
    {
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getPostMimeType();
    }

    /**
     * Set MIME type
     *
     * @param string $mimeType
     * @return static
     */
    public function setMimeType(string $mimeType): static
    {
        /** @var HasProxyPost $this */
        $this->proxyPost()->setPostMimeType($mimeType);

        return $this;
    }
}