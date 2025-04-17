<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;
use Charm\Enums\Post\Status;

/**
 * Indicates post has status.
 *
 * Table: wp_posts
 * Column: post_status
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasStatus
{
    /**
     * Get status (wp_posts: post_status)
     *
     * @return Status
     */
    public function getStatus(): Status
    {
        /** @var HasWpPost $this */
        return Status::from($this->wp()->getPostStatus());
    }

    /**
     * Set status (wp_posts: post_status)
     *
     * @param Status $status
     * @return static
     */
    public function setStatus(Status $status): static
    {
        /** @var HasWpPost $this */
        $this->wp()->setPostStatus($status->value);

        return $this;
    }
}