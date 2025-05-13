<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\Proxy\HasProxyUser;

/**
 * Adds display name to user model.
 *
 * Table: wp_users
 * Column: display_name
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithDisplayName
{
    /**
     * Get display name
     *
     * @return string
     */
    public function getDisplayName(): string
    {
        /** @var HasProxyUser $this */
        return $this->proxyUser()->getDisplayName();
    }

    /**
     * Set display name
     *
     * @param string $name
     * @return static
     */
    public function setDisplayName(string $name): static
    {
        /** @var HasProxyUser $this */
        $this->proxyUser()->setDisplayName($name);

        return $this;
    }
}