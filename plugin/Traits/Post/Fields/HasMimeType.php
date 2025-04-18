<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;

/**
 * Indicates that a post has a MIME type.
 *
 * Table: wp_posts
 * Column: post_mime_type
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasMimeType
{
    /**
     * Get MIME type
     *
     * @return string
     */
    public function getMimeType(): string
    {
        /** @var HasWpPost $this */
        return $this->wp()->getPostMimeType();
    }

    /**
     * Set MIME type
     *
     * @param string $mimeType
     * @return static
     */
    public function setMimeType(string $mimeType): static
    {
        /** @var HasWpPost $this */
        $this->wp()->setPostMimeType($mimeType);

        return $this;
    }
}