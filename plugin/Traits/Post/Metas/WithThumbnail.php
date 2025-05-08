<?php

namespace Charm\Traits\Post\Metas;

use Charm\Contracts\HasMeta;

/**
 * Adds thumbnail to post model.
 *
 * Table: wp_postmeta
 * Meta Key: _thumbnail_id
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithThumbnail
{
    /**
     * Get thumbnail ID
     */
    public function getThumbnailId(): int
    {
        /** @var HasMeta $this */
        return $this->getMeta('_thumbnail_id')->castValue()->toInt();
    }

    /**
     * Set thumbnail ID
     *
     * @param int $id
     * @return static
     */
    public function setThumbnailId(int $id): static
    {
        /** @var HasMeta $this */
        $this->updateMeta('_thumbnail_id', $id);

        return $this;
    }

    /**
     * Set thumbnail ID
     *
     * @return static
     */
    public function deleteThumbnailId(): static
    {
        /** @var HasMeta $this */
        $this->deleteMeta('_thumbnail_id');

        return $this;
    }
}