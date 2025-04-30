<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\HasWpUser;

/**
 * Indicates that a user has a password.
 *
 * Table: wp_users
 * Column: user_pass
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithPassword
{
    /**
     * Get password hash
     *
     * @return string|null
     */
    public function getPassword(): ?string
    {
        /** @var HasWpUser $this */
        return $this->wp()->getUserPass();
    }

    /**
     * Set password
     *
     * @param string $password
     * @return static
     */
    public function setPassword(string $password): static
    {
        /** @var HasWpUser $this */
        $this->wp()->setUserPass($password);

        return $this;
    }
}