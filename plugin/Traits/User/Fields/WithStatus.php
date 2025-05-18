<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\Proxy\HasProxyUser;
use Charm\Enums\User\Status;

/**
 * Adds the status to a user model.
 *
 * Table: `wp_users`
 * Column: `user_status`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithStatus
{
    /**
     * Get the user's status.
     *
     * @return Status
     */
    public function getStatus(): Status
    {
        /** @var HasProxyUser $this */
        return Status::from($this->proxyUser()->getUserStatus());
    }

    /**
     * Set the user's status.
     *
     * @param Status|int $status
     * @return static
     */
    public function setStatus(Status|int $status): static
    {
        $value = $status instanceof Status ? $status->value : $status;

        /** @var HasProxyUser $this */
        $this->proxyUser()->setUserStatus($value);

        return $this;
    }
}