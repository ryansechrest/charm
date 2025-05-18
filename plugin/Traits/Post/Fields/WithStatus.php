<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Proxy\HasProxyPost;
use Charm\Enums\Post\Status;

/**
 * Adds the status to a post model.
 *
 * Table: `wp_posts`
 * Column: `post_status`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithStatus
{
    /**
     * Get the post's status.
     *
     * @return Status
     */
    public function getStatus(): Status
    {
        /** @var HasProxyPost $this */
        return Status::from($this->proxyPost()->getPostStatus());
    }

    /**
     * Set the post's status.
     *
     * @param Status|string $status
     * @return static
     */
    public function setStatus(Status|string $status): static
    {
        $value = $status instanceof Status ? $status->value : $status;

        /** @var HasProxyPost $this */
        $this->proxyPost()->setPostStatus($value);

        return $this;
    }
}