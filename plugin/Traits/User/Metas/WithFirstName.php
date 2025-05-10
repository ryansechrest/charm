<?php

namespace Charm\Traits\User\Metas;

use Charm\Contracts\HasMeta;

/**
 * Adds first name to user model.
 *
 * Table: wp_usermeta
 * Meta Key: first_name
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithFirstName
{
    /**
     * Get first name
     */
    public function getFirstName(): string
    {
        /** @var HasMeta $this */
        return $this->getMeta(key: 'first_name')->castValue()->toString();
    }

    /**
     * Set first name
     *
     * @param string $firstName
     * @return static
     */
    public function setFirstName(string $firstName): static
    {
        /** @var HasMeta $this */
        $this->updateMeta(key: 'first_name', value: $firstName);

        return $this;
    }

    /**
     * Delete first name
     *
     * @return static
     */
    public function deleteFirstName(): static
    {
        /** @var HasMeta $this */
        $this->deleteMeta(key: 'first_name');

        return $this;
    }
}