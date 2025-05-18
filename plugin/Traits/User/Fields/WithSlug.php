<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\Proxy\HasProxyUser;

/**
 * Adds the slug to a user model.
 *
 * Table: `wp_users`
 * Column: `user_nicename`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithSlug
{
    /**
     * Get the user's URL slug.
     *
     * @return string
     */
    public function getSlug(): string
    {
        /** @var HasProxyUser $this */
        return $this->proxyUser()->getUserNicename();
    }

    /**
     * Set user's URL slug.
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