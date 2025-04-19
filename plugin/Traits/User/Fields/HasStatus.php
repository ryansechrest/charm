<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\HasWpUser;
use Charm\Enums\User\Status;

/**
 * Indicates that a user has a status.
 *
 * Table: wp_users
 * Column: user_status
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasStatus
{
    /**
     * Get status
     *
     * @return Status
     */
    public function getStatus(): Status
    {
        /** @var HasWpUser $this */
        return Status::from($this->wp()->getUserStatus());
    }

    /**
     * Set status
     *
     * @param Status|int $status
     * @return static
     */
    public function setStatus(Status|int $status): static
    {
        $value = $status instanceof Status ? $status->value : $status;

        /** @var HasWpUser $this */
        $this->wp()->setUserStatus($value);

        return $this;
    }
}