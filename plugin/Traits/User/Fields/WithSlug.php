<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\Core\HasCoreUser;

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
        /** @var HasCoreUser $this */
        return $this->coreUser()->getUserNicename();
    }

    /**
     * Set user's URL slug.
     *
     * @param string $slug
     * @return static
     */
    public function setSlug(string $slug): static
    {
        /** @var HasCoreUser $this */
        $this->coreUser()->setUserNicename($slug);

        return $this;
    }
}