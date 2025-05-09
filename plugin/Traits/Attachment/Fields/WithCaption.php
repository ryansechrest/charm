<?php

namespace Charm\Traits\Attachment\Fields;

use Charm\Contracts\HasWpPost;

/**
 * Adds caption to attachment model.
 *
 * Table: wp_posts
 * Column: post_excerpt
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithCaption
{
    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption(): string
    {
        /** @var HasWpPost $this */
        return $this->wp()->getPostExcerpt();
    }

    /**
     * Set caption
     *
     * @param string $caption
     * @return static
     */
    public function setCaption(string $caption): static
    {
        /** @var HasWpPost $this */
        $this->wp()->setPostExcerpt($caption);

        return $this;
    }
}