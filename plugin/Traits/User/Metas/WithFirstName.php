<?php

namespace Charm\Traits\User\Metas;

use Charm\Contracts\HasMeta;

/**
 * Indicates that a user has a first name.
 *
 * Table: wp_usermeta
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
        return $this->getMeta('first_name')->castValue()->toString();
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
        $this->setMeta('first_name', $firstName);

        return $this;
    }
}