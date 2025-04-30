<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\HasWpUser;

/**
 * Indicates that a user has an activation key.
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
        /** @var HasWpUser $this */
        return $this->wp()->getUserActivationKey();
    }

    /**
     * Set activation key
     *
     * @param string $key
     * @return static
     */
    public function setActivationKey(string $key): static
    {
        /** @var HasWpUser $this */
        $this->wp()->setUserActivationKey($key);

        return $this;
    }
}
