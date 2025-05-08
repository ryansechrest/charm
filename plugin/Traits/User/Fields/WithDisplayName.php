<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\HasWpUser;

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