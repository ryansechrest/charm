<?php

namespace Charm\Traits\User\Metas;

use Charm\Contracts\HasMeta;

/**
 * Adds last name to user model.
 *
 * Table: wp_usermeta
 * Meta Key: last_name
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithLastName
{
    /**
     * Get last name
     */
    public function getLastName(): string
    {
        /** @var HasMeta $this */
        return $this->getMeta('last_name')->castValue()->toString();
    }

    /**
     * Set last name
     *
     * @param string $lastName
     * @return static
     */
    public function setLastName(string $lastName): static
    {
        /** @var HasMeta $this */
        $this->updateMeta('last_name', $lastName);

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
        $this->deleteMeta('last_name');

        return $this;
    }
}