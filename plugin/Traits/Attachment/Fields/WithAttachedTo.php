<?php

namespace Charm\Traits\Attachment\Fields;

use Charm\Contracts\HasProxyPost;

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
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getPostParent();
    }

    /**
     * Set ID of attached model
     *
     * @param int $postId
     * @return static
     */
    public function setAttachedTo(int $postId): static
    {
        /** @var HasProxyPost $this */
        $this->proxyPost()->setPostParent($postId);

        return $this;
    }
}