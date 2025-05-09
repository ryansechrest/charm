<?php

namespace Charm\Traits\Attachment\Metas;

use Charm\Contracts\HasMeta;

/**
 * Adds file path to attachment model.
 *
 * Table: wp_postmeta
 * Meta Key: _wp_attachment_metadata
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithMetaData
{
    /**
     * Get meta data
     */
    public function getMetaData(): array
    {
        /** @var HasMeta $this */
        return $this->getMeta('_wp_attachment_metadata')->castValue()->toArray();
    }

    /**
     * Set meta data
     *
     * @param array $metaData
     * @return static
     */
    public function setMetaData(array $metaData): static
    {
        /** @var HasMeta $this */
        $this->updateMeta('_wp_attachment_metadata', $metaData);

        return $this;
    }
}