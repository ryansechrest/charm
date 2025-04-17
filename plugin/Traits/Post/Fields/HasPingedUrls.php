<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;
use Charm\Enums\Post\PingStatus;

/**
 * Indicates post has pinged URLs.
 *
 * Table: wp_posts
 * Columns: ping_status, pinged, to_ping
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasPingedUrls
{
    /**
     * Get ping status
     *
     * @return PingStatus
     */
    public function getPingStatus(): PingStatus
    {
        /** @var HasWpPost $this */
        return PingStatus::from($this->wp()->getPingStatus());
    }

    /**
     * Set ping status
     *
     * @param PingStatus $status
     * @return static
     */
    public function setPingStatus(PingStatus $status): static
    {
        /** @var HasWpPost $this */
        $this->wp()->setPingStatus($status->value);

        return $this;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get pinged URLs
     *
     * @return string[]
     */
    public function getPingedUrls(): array
    {
        /** @var HasWpPost $this */
        return explode("\n", $this->wp()->getPinged());
    }

    /**
     * Set pinged URLs
     *
     * @param string[] $urls
     * @return static
     */
    public function setPingedUrls(array $urls): static
    {
        /** @var HasWpPost $this */
        $this->wp()->setPinged(implode("\n", $urls));

        return $this;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get URLs to ping
     *
     * @return string[]
     */
    public function getUrlsToPing(): array
    {
        /** @var HasWpPost $this */
        return explode("\n", $this->wp()->getToPing());
    }

    /**
     * Set URLs to ping
     *
     * @param string[] $urls
     * @return static
     */
    public function setUrlsToPing(array $urls): static
    {
        /** @var HasWpPost $this */
        $this->wp()->setToPing(implode("\n", $urls));

        return $this;
    }
}