<?php

namespace Charm\Traits\Attachment\Metas;

use Charm\Contracts\HasMeta;

/**
 * Adds file path to attachment model.
 *
 * Table: wp_postmeta
 * Meta Key: _wp_attached_file
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithFilePath
{
    /**
     * Get file path
     */
    public function getFilePath(): string
    {
        /** @var HasMeta $this */
        return $this->getMeta('_wp_attached_file')->castValue()->toString();
    }

    /**
     * Set file path
     *
     * @param string $filePath
     * @return static
     */
    public function setFilePath(string $filePath): static
    {
        /** @var HasMeta $this */
        $this->updateMeta('_wp_attached_file', $filePath);

        return $this;
    }
}