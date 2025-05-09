<?php

namespace Charm\Traits\Attachment\Metas;

use Charm\Contracts\HasMeta;

/**
 * Adds alternative text to attachment model.
 *
 * Table: wp_postmeta
 * Meta Key: _wp_attachment_image_alt
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithAltText
{
    /**
     * Get alternative text
     */
    public function getAltText(): string
    {
        /** @var HasMeta $this */
        return $this->getMeta('_wp_attachment_image_alt')->castValue()->toString();
    }

    /**
     * Set alternative text
     *
     * @param string $altText
     * @return static
     */
    public function setAltText(string $altText): static
    {
        /** @var HasMeta $this */
        $this->updateMeta('_wp_attachment_image_alt', $altText);

        return $this;
    }
}