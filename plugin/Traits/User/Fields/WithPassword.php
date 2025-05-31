<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\Core\HasCoreUser;

/**
 * Adds the password to a user model.
 *
 * Table: `wp_users`
 * Column: `user_pass`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithPassword
{
    /**
     * Get the user's hashed password.
     *
     * @return string|null
     */
    public function getPassword(): ?string
    {
        /** @var HasCoreUser $this */
        return $this->coreUser()->getUserPass();
    }

    /**
     * Set the user's password.
     *
     * @param string $password
     * @return static
     */
    public function setPassword(string $password): static
    {
        /** @var HasCoreUser $this */
        $this->coreUser()->setUserPass($password);

        return $this;
    }
}