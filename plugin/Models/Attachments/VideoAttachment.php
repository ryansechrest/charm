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
     * Get video width
     */
    public function getWidth(): string
    {
        return $this->getMetaData()['width'] ?? '';
    }

    // -------------------------------------------------------------------------

    /**
     * Get video height
     */
    public function getHeight(): string
    {
        return $this->getMetaData()['height'] ?? '';
    }

    // -------------------------------------------------------------------------

    /**
     * Get length (in seconds)
     */
    public function getLength(): string
    {
        return $this->getMetaData()['length'] ?? '';
    }
}