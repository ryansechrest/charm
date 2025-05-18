<?php

namespace Charm\Traits\Attachment\Fields;

use Charm\Contracts\Proxy\HasProxyPost;

/**
 * Adds the MIME type to an attachment model.
 *
 * Table: `wp_posts`
 * Column: `post_mime_type`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithMimeType
{
    /**
     * Get the attachment's MIME type.
     *
     * @return string
     */
    public function getMimeType(): string
    {
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getPostMimeType();
    }

    /**
     * Set the attachment's MIME type.
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