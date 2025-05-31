<?php

namespace Charm\Traits\Attachment\Fields;

use Charm\Contracts\Core\HasCorePost;

/**
 * Adds the caption to an attachment model.
 *
 * Table: `wp_posts`
 * Column: `post_excerpt`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithCaption
{
    /**
     * Get the attachment's caption.
     *
     * @return string
     */
    public function getCaption(): string
    {
        /** @var HasCorePost $this */
        return $this->corePost()->getPostExcerpt();
    }

    /**
     * Set the attachment's caption.
     *
     * @param string $caption
     * @return static
     */
    public function setCaption(string $caption): static
    {
        /** @var HasCorePost $this */
        $this->corePost()->setPostExcerpt($caption);

        return $this;
    }
}