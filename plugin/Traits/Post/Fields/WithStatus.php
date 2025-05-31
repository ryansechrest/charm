<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Core\HasCorePost;
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
        /** @var HasCorePost $this */
        return Status::from($this->corePost()->getPostStatus());
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

        /** @var HasCorePost $this */
        $this->corePost()->setPostStatus($value);

        return $this;
    }
}