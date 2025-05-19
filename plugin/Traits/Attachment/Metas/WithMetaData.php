<?php

namespace Charm\Traits\Attachment\Metas;

use Charm\Contracts\HasMeta;

/**
 * Adds the metadata to an attachment model.
 *
 * Table: `wp_postmeta`
 * Meta Key: `_wp_attachment_metadata`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithMetaData
{
    /**
     * Get the metadata.
     */
    public function getMetaData(): array
    {
        /** @var HasMeta $this */
        return $this
            ->getMeta(key: '_wp_attachment_metadata')
            ->castValue()
            ->toArray();
    }

    /**
     * Set the metadata.
     *
     * @param array $metaData
     * @return static
     */
    public function setMetaData(array $metaData): static
    {
        /** @var HasMeta $this */
        $this->updateMeta(key: '_wp_attachment_metadata', value: $metaData);

        return $this;
    }
}