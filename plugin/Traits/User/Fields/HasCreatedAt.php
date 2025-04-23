<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\HasWpUser;
use Charm\Utilities\DateTime;

/**
 * Indicates that a user has a created date.
 *
 * Table: wp_users
 * Column: user_registered
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasCreatedAt
{
    /**
     * Get created date
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        /** @var HasWpUser $this */
        return DateTime::init($this->wp()->getUserRegistered());
    }

    /**
     * Set created date
     *
     * @param DateTime|string $dateTime
     * @return static
     */
    public function setCreatedAt(DateTime|string $dateTime): static
    {
        $value = $dateTime instanceof DateTime
            ? $dateTime->formatForDb()
            : $dateTime;

        /** @var HasWpUser $this */
        $this->wp()->setUserRegistered($value);

        return $this;
    }
}