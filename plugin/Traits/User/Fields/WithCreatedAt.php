<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\Proxy\HasProxyUser;
use Charm\Utilities\DateTime;

/**
 * Adds created date to user model.
 *
 * Table: wp_users
 * Column: user_registered
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithCreatedAt
{
    /**
     * Get created date
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        /** @var HasProxyUser $this */
        return DateTime::init($this->proxyUser()->getUserRegistered());
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

        /** @var HasProxyUser $this */
        $this->proxyUser()->setUserRegistered($value);

        return $this;
    }
}