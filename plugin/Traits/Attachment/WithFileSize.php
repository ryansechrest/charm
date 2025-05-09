<?php

namespace Charm\Traits\Attachment;

/**
 * Adds file size to attachment model.
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
     * Get file size
     *
     * @return int 297532
     */
    public function getFileSize(): int
    {
        return $this->getMetaData()['filesize'] ?? 0;
    }
}