<?php

namespace Charm\Traits\Attachment\Fields;

use Charm\Contracts\Proxy\HasProxyPost;

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
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getPostParent();
    }

    /**
     * Set the ID of the model the attachment belongs to.
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