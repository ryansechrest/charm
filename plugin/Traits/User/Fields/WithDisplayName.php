<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\Core\HasCoreUser;

/**
 * Adds the display name to a user model.
 *
 * Table: `wp_users`
 * Column: `display_name`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithDisplayName
{
    /**
     * Get the user's display name.
     *
     * @return string
     */
    public function getDisplayName(): string
    {
        /** @var HasCoreUser $this */
        return $this->coreUser()->getDisplayName();
    }

    /**
     * Set the user's display name.
     *
     * @param string $name
     * @return static
     */
    public function setDisplayName(string $name): static
    {
        /** @var HasCoreUser $this */
        $this->coreUser()->setDisplayName($name);

        return $this;
    }
}