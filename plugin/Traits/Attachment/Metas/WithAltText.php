<?php

namespace Charm\Traits\Attachment\Metas;

use Charm\Contracts\HasMeta;

/**
 * Adds the alternative text to an attachment model.
 *
 * Table: `wp_postmeta`
 * Meta Key: `_wp_attachment_image_alt`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithAltText
{
    /**
     * Get the alternative text.
     */
    public function getAltText(): string
    {
        /** @var HasMeta $this */
        return $this
            ->getMeta(key: '_wp_attachment_image_alt')
            ->castValue()
            ->toString();
    }

    /**
     * Set the alternative text.
     *
     * @param string $altText
     * @return static
     */
    public function setAltText(string $altText): static
    {
        /** @var HasMeta $this */
        $this->updateMeta(key: '_wp_attachment_image_alt', value: $altText);

        return $this;
    }
}