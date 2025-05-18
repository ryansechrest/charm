<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Proxy\HasProxyPost;
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
        /** @var HasProxyPost $this */
        return CommentStatus::from($this->proxyPost()->getCommentStatus());
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

        /** @var HasProxyPost $this */
        $this->proxyPost()->setCommentStatus($value);

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
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getCommentCount();
    }
}