<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\HasWpUser;

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