<?php

namespace Charm\Traits\Post\Metas;

use Charm\Contracts\HasMeta;
use Charm\Models\Attachments\ImageAttachment;

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
     * Get thumbnail
     */
    public function getThumbnail(): ImageAttachment
    {
        /** @var HasMeta $this */
        $thumbnailId = $this
            ->getMeta(key: '_thumbnail_id')
            ->castValue()
            ->toInt();

        return ImageAttachment::init($thumbnailId);
    }

    /**
     * Set thumbnail
     *
     * @param ImageAttachment|int $thumbnail
     * @return static
     */
    public function setThumbnail(ImageAttachment|int $thumbnail): static
    {
        /** @var HasMeta $this */
        $id = $thumbnail instanceof ImageAttachment
            ? $thumbnail->getId() : $thumbnail;

        $this->updateMeta(key: '_thumbnail_id', value: $id);

        return $this;
    }

    /**
     * Delete thumbnail
     *
     * @return static
     */
    public function deleteThumbnail(): static
    {
        /** @var HasMeta $this */
        $this->deleteMeta(key: '_thumbnail_id');

        return $this;
    }
}