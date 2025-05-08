<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\HasWpUser;

/**
 * Adds username to user model.
 *
 * Table: wp_users
 * Column: user_login
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithUsername
{
    /**
     * Get username
     *
     * @return string
     */
    public function getUsername(): string
    {
        /** @var HasWpUser $this */
        return $this->wp()->getUserLogin();
    }

    /**
     * Set username
     *
     * @param string $username
     * @return static
     */
    public function setUsername(string $username): static
    {
        /** @var HasWpUser $this */
        $this->wp()->setUserLogin($username);

        return $this;
    }
}