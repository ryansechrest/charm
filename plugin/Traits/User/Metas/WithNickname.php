<?php

namespace Charm\Traits\User\Metas;

use Charm\Contracts\HasMeta;

/**
 * Indicates that a user has a nickname.
 *
 * Table: wp_usermeta
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithNickname
{
    /**
     * Get nickname
     */
    public function getNickname(): string
    {
        /** @var HasMeta $this */
        return $this->getMeta('nickname')->castValue()->toString();
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     * @return static
     */
    public function setNickname(string $nickname): static
    {
        /** @var HasMeta $this */
        $this->setMeta('nickname', $nickname);

        return $this;
    }
}