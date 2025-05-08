<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;
use Charm\Enums\Post\Status;

/**
 * Adds status to post model.
 *
 * Table: wp_posts
 * Column: post_status
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithStatus
{
    /**
     * Get status
     *
     * @return Status
     */
    public function getStatus(): Status
    {
        /** @var HasWpPost $this */
        return Status::from($this->wp()->getPostStatus());
    }

    /**
     * Set status
     *
     * @param Status|string $status
     * @return static
     */
    public function setStatus(Status|string $status): static
    {
        $value = $status instanceof Status ? $status->value : $status;

        /** @var HasWpPost $this */
        $this->wp()->setPostStatus($value);

        return $this;
    }
}