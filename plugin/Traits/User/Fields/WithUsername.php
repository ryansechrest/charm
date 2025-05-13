<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\HasProxyUser;

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
        /** @var HasProxyUser $this */
        return $this->proxyUser()->getUserLogin();
    }

    /**
     * Set username
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