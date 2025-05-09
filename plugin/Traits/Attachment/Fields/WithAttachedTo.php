<?php

namespace Charm\Traits\Attachment\Fields;

use Charm\Contracts\HasWpPost;

/**
 * Adds attachment context to attachment model.
 *
 * Table: wp_posts
 * Column: post_parent
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithAttachedTo
{
    /**
     * Get ID of attached model
     *
     * @return int
     */
    public function getAttachedTo(): int
    {
        /** @var HasWpPost $this */
        return $this->wp()->getPostParent();
    }

    /**
     * Set ID of attached model
     *
     * @param int $postId
     * @return static
     */
    public function setAttachedTo(int $postId): static
    {
        /** @var HasWpPost $this */
        $this->wp()->setPostParent($postId);

        return $this;
    }
}