<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\HasWpUser;

/**
 * Indicates that a user has an ID.
 *
 * Table: wp_users
 * Column: ID
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasId
{
    /**
     * Get ID
     *
     * @return int
     */
    public function getId(): int
    {
        /** @var HasWpUser $this */
        return $this->wp()->getId();
    }
}