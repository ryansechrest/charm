<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\HasWpUser;

/**
 * Indicates that a user has an email address.
 *
 * Table: wp_users
 * Column: user_email
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithEmail
{
    /**
     * Get email
     *
     * @return string
     */
    public function getEmail(): string
    {
        /** @var HasWpUser $this */
        return $this->wp()->getUserEmail();
    }

    /**
     * Set email
     *
     * @param string $email
     * @return static
     */
    public function setEmail(string $email): static
    {
        /** @var HasWpUser $this */
        $this->wp()->setUserEmail($email);

        return $this;
    }
}