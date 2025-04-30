<?php

namespace Charm\Traits\User\Metas;

use Charm\Contracts\HasMeta;

/**
 * Indicates that a user has a last name.
 *
 * Table: wp_usermeta
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
        $this->setMeta('last_name', $lastName);

        return $this;
    }
}