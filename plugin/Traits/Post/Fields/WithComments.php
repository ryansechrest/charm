<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Core\HasCorePost;
use Charm\Enums\Post\CommentStatus;

/**
 * Adds comments to a post model.
 *
 * Table: `wp_posts`
 * Columns: `comment_status`, `comment_count`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithComments
{
    /**
     * Get the post's comment status.
     *
     * @return CommentStatus
     */
    public function getCommentStatus(): CommentStatus
    {
        /** @var HasCorePost $this */
        return CommentStatus::from($this->corePost()->getCommentStatus());
    }

    /**
     * Set the post's comment status.
     *
     * @param CommentStatus|string $status
     * @return static
     */
    public function setCommentStatus(CommentStatus|string $status): static
    {
        $value = $status instanceof CommentStatus ? $status->value : $status;

        /** @var HasCorePost $this */
        $this->corePost()->setCommentStatus($value);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the number of comments on the post.
     *
     * @return int
     */
    public function getCommentCount(): int
    {
        /** @var HasCorePost $this */
        return $this->corePost()->getCommentCount();
    }
}