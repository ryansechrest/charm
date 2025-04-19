<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\HasWpUser;

/**
 * Indicates that a user has a slug.
 *
 * Table: wp_users
 * Column: user_nicename
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasSlug
{
    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug(): string
    {
        /** @var HasWpUser $this */
        return $this->wp()->getUserNicename();
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return static
     */
    public function setSlug(string $slug): static
    {
        /** @var HasWpUser $this */
        $this->wp()->setUserNicename($slug);

        return $this;
    }
}