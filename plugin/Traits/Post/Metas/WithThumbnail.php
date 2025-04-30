<?php

namespace Charm\Traits\Post\Metas;

use Charm\Contracts\HasMeta;

/**
 * Indicates that a post has a thumbnail.
 *
 * Table: wp_postmeta
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
        $this->setMeta('_thumbnail_id', $id);

        return $this;
    }
}