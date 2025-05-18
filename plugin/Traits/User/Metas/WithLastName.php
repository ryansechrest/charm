<?php

namespace Charm\Traits\User\Metas;

use Charm\Contracts\HasMeta;

/**
 * Adds the last name to a user model.
 *
 * Table: `wp_usermeta`
 * Meta Key: `last_name`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithLastName
{
    /**
     * Get the user's last name.
     */
    public function getLastName(): string
    {
        /** @var HasMeta $this */
        return $this->getMeta(key: 'last_name')->castValue()->toString();
    }

    /**
     * Set the user's last name.
     *
     * @param string $lastName
     * @return static
     */
    public function setLastName(string $lastName): static
    {
        /** @var HasMeta $this */
        $this->updateMeta(key: 'last_name', value: $lastName);

        return $this;
    }

    /**
     * Delete last name
     *
     * @return static
     */
    public function deleteLastName(): static
    {
        /** @var HasMeta $this */
        $this->deleteMeta(key: 'last_name');

        return $this;
    }
}