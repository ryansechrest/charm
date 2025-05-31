<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\Core\HasCoreUser;

/**
 * Adds the email address to a user model.
 *
 * Table: `wp_users`
 * Column: `user_email`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithEmail
{
    /**
     * Get the user's email address.
     *
     * @return string
     */
    public function getEmail(): string
    {
        /** @var HasCoreUser $this */
        return $this->coreUser()->getUserEmail();
    }

    /**
     * Set the user's email address.
     *
     * @param string $email
     * @return static
     */
    public function setEmail(string $email): static
    {
        /** @var HasCoreUser $this */
        $this->coreUser()->setUserEmail($email);

        return $this;
    }
}