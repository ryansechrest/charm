<?php

namespace Charm\Models\Attachments;

/**
 * Represents a video attachment in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class VideoAttachment extends Attachment
{
    /**
     * Get video's width in pixels.
     */
    public function getWidth(): string
    {
        return $this->getMetaData()['width'] ?? '';
    }

    // -------------------------------------------------------------------------

    /**
     * Get the video's height in pixels.
     */
    public function getHeight(): string
    {
        return $this->getMetaData()['height'] ?? '';
    }

    // -------------------------------------------------------------------------

    /**
     * Get the video's length in seconds.
     */
    public function getLength(): string
    {
        return $this->getMetaData()['length'] ?? '';
    }
}