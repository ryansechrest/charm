<?php

namespace Charm\Models\Attachments;

/**
 * Represents an audio attachment in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class AudioAttachment extends Attachment
{
    /**
     * Get the album's name.
     */
    public function getAlbum(): string
    {
        return $this->getMetaData()['album'] ?? '';
    }

    // -------------------------------------------------------------------------

    /**
     * Get the artist's name.
     */
    public function getArist(): string
    {
        return $this->getMetaData()['artist'] ?? '';
    }

    // -------------------------------------------------------------------------

    /**
     * Get the audio's length in seconds.
     */
    public function getLength(): string
    {
        return $this->getMetaData()['length'] ?? '';
    }
}