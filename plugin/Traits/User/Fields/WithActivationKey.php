<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\HasProxyUser;

/**
 * Adds activation key to user model.
 *
 * Table: wp_users
 * Column: user_activation_key
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithActivationKey
{
    /**
     * Get activation key
     *
     * @return string|null
     */
    public function getActivationKey(): ?string
    {
        /** @var HasProxyUser $this */
        return $this->proxyUser()->getUserActivationKey();
    }

    /**
     * Set activation key
     *
     * @param string $key
     * @return static
     */
    public function setActivationKey(string $key): static
    {
        /** @var HasProxyUser $this */
        $this->proxyUser()->setUserActivationKey($key);

        return $this;
    }
}
