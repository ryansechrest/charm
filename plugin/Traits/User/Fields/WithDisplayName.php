<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\HasWpUser;

/**
 * Indicates that a user has a display name.
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
        /** @var HasWpUser $this */
        return $this->wp()->getDisplayName();
    }

    /**
     * Set display name
     *
     * @param string $name
     * @return static
     */
    public function setDisplayName(string $name): static
    {
        /** @var HasWpUser $this */
        $this->wp()->setDisplayName($name);

        return $this;
    }
}