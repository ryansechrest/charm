<?php

namespace Charm\Traits\Attachment\Fields;

use Charm\Contracts\Core\HasCorePost;

/**
 * Adds the attachment context to an attachment model.
 *
 * Table: `wp_posts`
 * Column: `post_parent`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithAttachedTo
{
    /**
     * Get the ID of the model the attachment belongs to.
     *
     * @return int
     */
    public function getAttachedTo(): int
    {
        /** @var HasCorePost $this */
        return $this->corePost()->getPostParent();
    }

    /**
     * Set the ID of the model the attachment belongs to.
     *
     * @param int $postId
     * @return static
     */
    public function setAttachedTo(int $postId): static
    {
        /** @var HasCorePost $this */
        $this->corePost()->setPostParent($postId);

        return $this;
    }
}