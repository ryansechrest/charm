<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\Proxy\HasProxyUser;

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
        /** @var HasProxyUser $this */
        return $this->proxyUser()->getUserEmail();
    }

    /**
     * Set the user's email address.
     *
     * @param string $email
     * @return static
     */
    public function setEmail(string $email): static
    {
        /** @var HasProxyUser $this */
        $this->proxyUser()->setUserEmail($email);

        return $this;
    }
}