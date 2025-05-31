<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\Core\HasCoreUser;
use Charm\Utilities\DateTime;

/**
 * Adds the created date to a user model.
 *
 * Table: `wp_users`
 * Column: `user_registered`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithCreatedAt
{
    /**
     * Get the date and time the user was created.
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        /** @var HasCoreUser $this */
        return DateTime::init($this->coreUser()->getUserRegistered());
    }

    /**
     * Set the date and time the user was created.
     *
     * @param DateTime|string $dateTime
     * @return static
     */
    public function setCreatedAt(DateTime|string $dateTime): static
    {
        $value = $dateTime instanceof DateTime
            ? $dateTime->formatForDb()
            : $dateTime;

        /** @var HasCoreUser $this */
        $this->coreUser()->setUserRegistered($value);

        return $this;
    }
}