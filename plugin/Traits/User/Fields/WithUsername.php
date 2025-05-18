<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\Proxy\HasProxyUser;

/**
 * Adds the username to a user model.
 *
 * Table: `wp_users`
 * Column: `user_login`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithUsername
{
    /**
     * Get the user's username.
     *
     * @return string
     */
    public function getUsername(): string
    {
        /** @var HasProxyUser $this */
        return $this->proxyUser()->getUserLogin();
    }

    /**
     * Set the user's username.
     *
     * @param string $username
     * @return static
     */
    public function setUsername(string $username): static
    {
        /** @var HasProxyUser $this */
        $this->proxyUser()->setUserLogin($username);

        return $this;
    }
}