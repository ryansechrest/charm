<?php

namespace Charm\Traits\User\Metas;

use Charm\Contracts\HasMeta;

/**
 * Adds biographical information to user model.
 *
 * Table: wp_usermeta
 * Meta Key: description
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithBioInfo
{
    /**
     * Get biographical info
     */
    public function getBioInfo(): string
    {
        /** @var HasMeta $this */
        return $this->getMeta(key: 'description')->castValue()->toString();
    }

    /**
     * Set biographical info
     *
     * @param string $bioInfo
     * @return static
     */
    public function setBioInfo(string $bioInfo): static
    {
        /** @var HasMeta $this */
        $this->updateMeta(key: 'description', value: $bioInfo);

        return $this;
    }

    /**
     * Delete biographical info
     *
     * @return static
     */
    public function deleteBioInfo(): static
    {
        /** @var HasMeta $this */
        $this->deleteMeta(key: 'description');

        return $this;
    }
}