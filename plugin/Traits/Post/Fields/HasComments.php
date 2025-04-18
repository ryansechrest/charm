<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;
use Charm\Enums\Post\CommentStatus;

/**
 * Indicates that a post has comments.
 *
 * Table: wp_posts
 * Columns: comment_status, comment_count
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasComments
{
    /**
     * Get comment status
     *
     * @return CommentStatus
     */
    public function getCommentStatus(): CommentStatus
    {
        /** @var HasWpPost $this */
        return CommentStatus::from($this->wp()->getCommentStatus());
    }

    /**
     * Set comment status
     *
     * @param CommentStatus $status
     * @return static
     */
    public function setCommentStatus(CommentStatus $status): static
    {
        /** @var HasWpPost $this */
        $this->wp()->setCommentStatus($status->value);

        return $this;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get comment count
     *
     * @return int
     */
    public function getCommentCount(): int
    {
        /** @var HasWpPost $this */
        return $this->wp()->getCommentCount();
    }
}