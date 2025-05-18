<?php

namespace Charm\Traits\Attachment\Metas;

use Charm\Contracts\HasMeta;

/**
 * Adds the file path to an attachment model.
 *
 * Table: `wp_postmeta`
 * Meta Key: `_wp_attached_file`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithFilePath
{
    /**
     * Get the file path.
     */
    public function getFilePath(): string
    {
        /** @var HasMeta $this */
        return $this
            ->getMeta(key: '_wp_attached_file')
            ->castValue()
            ->toString();
    }

    /**
     * Set the file path.
     *
     * @param string $filePath
     * @return static
     */
    public function setFilePath(string $filePath): static
    {
        /** @var HasMeta $this */
        $this->updateMeta(key: '_wp_attached_file', value: $filePath);

        return $this;
    }
}