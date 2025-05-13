<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\Proxy\HasProxyUser;

/**
 * Adds password to user model.
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
        /** @var HasProxyUser $this */
        return $this->proxyUser()->getUserPass();
    }

    /**
     * Set password
     *
     * @param string $password
     * @return static
     */
    public function setPassword(string $password): static
    {
        /** @var HasProxyUser $this */
        $this->proxyUser()->setUserPass($password);

        return $this;
    }
}