<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\HasProxyUser;

/**
 * Adds slug to user model.
 *
 * Table: wp_users
 * Column: user_nicename
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithSlug
{
    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug(): string
    {
        /** @var HasProxyUser $this */
        return $this->proxyUser()->getUserNicename();
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return static
     */
    public function setSlug(string $slug): static
    {
        /** @var HasProxyUser $this */
        $this->proxyUser()->setUserNicename($slug);

        return $this;
    }
}