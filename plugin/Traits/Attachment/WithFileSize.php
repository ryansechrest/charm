<?php

namespace Charm\Traits\Attachment;

/**
 * Adds the file size to an attachment model.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithFileSize
{
    /**
     * Implemented in `WithMetaData` trait.
     */
    abstract public function getMetaData() : array;

    // *************************************************************************

    /**
     * Get the file size.
     *
     * @return int 297532
     */
    public function getFileSize(): int
    {
        return $this->getMetaData()['filesize'] ?? 0;
    }
}