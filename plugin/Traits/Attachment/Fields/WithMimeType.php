<?php

namespace Charm\Traits\Attachment\Fields;

use Charm\Contracts\Core\HasCorePost;

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
        /** @var HasCorePost $this */
        return $this->corePost()->getPostMimeType();
    }

    /**
     * Set the attachment's MIME type.
     *
     * @param string $mimeType
     * @return static
     */
    public function setMimeType(string $mimeType): static
    {
        /** @var HasCorePost $this */
        $this->corePost()->setPostMimeType($mimeType);

        return $this;
    }
}