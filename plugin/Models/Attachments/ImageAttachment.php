<?php

namespace Charm\Models\Attachments;

/**
 * Represents an image attachment in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class ImageAttachment extends Attachment
{
    /**
     * Get the image's width in pixels.
     */
    public function getWidth(): string
    {
        return $this->getMetaData()['width'] ?? '';
    }

    // -------------------------------------------------------------------------

    /**
     * Get the image's height in pixels.
     */
    public function getHeight(): string
    {
        return $this->getMetaData()['height'] ?? '';
    }

    // -------------------------------------------------------------------------

    /**
     * Get the image in its default or specified size.
     *
     * @param string $size medium
     * @return ?object
     */
    public function getImage(string $size = 'default'): ?object
    {
        return $this->getImages()[$size] ?: null;
    }

    /**
     * Get the image in every size.
     *
     * @return array
     */
    public function getImages(): array
    {
        $images['default'] = (object) [
            'path' => $this->getMetaData()['file'] ?? '',
            'width' => $this->getMetaData()['width'] ?? 0,
            'height' => $this->getMetaData()['height'] ?? 0,
            'size' => $this->getMetaData()['filesize'] ?? 0,
        ];

        $sizes = $this->getMetaData()['sizes'] ?? [];

        if (count($sizes) === 0) {
            return $images;
        }

        foreach ($sizes as $size => $image) {
            $images[$size] = (object) [
                'path' => $image['file'] ?? '',
                'width' => $image['width'] ?? 0,
                'height' => $image['height'] ?? 0,
                'size' => $image['filesize'] ?? 0,
            ];
        }

        return $images;
    }
}