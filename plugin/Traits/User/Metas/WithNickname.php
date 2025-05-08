<?php

namespace Charm\Traits\User\Metas;

use Charm\Contracts\HasMeta;

/**
 * Adds nickname to user model.
 *
 * Table: wp_usermeta
 * Meta Key: nickname
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
        $this->updateMeta('nickname', $nickname);

        return $this;
    }

    /**
     * Delete nickname
     *
     * @return static
     */
    public function deleteNickname(): static
    {
        /** @var HasMeta $this */
        $this->deleteMeta('nickname');

        return $this;
    }
}