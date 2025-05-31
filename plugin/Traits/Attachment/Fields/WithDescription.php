<?php

namespace Charm\Traits\Attachment\Fields;

use Charm\Contracts\Core\HasCorePost;

/**
 * Adds the description to an attachment model.
 *
 * Table: `wp_posts`
 * Column: `post_content`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithDescription
{
    /**
     * Get the attachment's description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        /** @var HasCorePost $this */
        return $this->corePost()->getPostContent();
    }

    /**
     * Set the attachment's description.
     *
     * @param string $description
     * @return static
     */
    public function setDescription(string $description): static
    {
        /** @var HasCorePost $this */
        $this->corePost()->setPostContent($description);

        return $this;
    }
}