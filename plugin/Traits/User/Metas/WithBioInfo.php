<?php

namespace Charm\Traits\User\Metas;

use Charm\Contracts\HasMeta;

/**
 * Indicates that a user has biographical info.
 *
 * Table: wp_usermeta
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
        return $this->getMeta('description')->castValue()->toString();
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
        $this->setMeta('description', $bioInfo);

        return $this;
    }
}