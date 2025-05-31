<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Core\HasCorePost;
use Charm\Enums\Post\PingStatus;

/**
 * Adds pingbacks to a post model.
 *
 * Table: `wp_posts`
 * Columns: `ping_status`, `pinged`, `to_ping`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithPingbacks
{
    /**
     * Get the post's pingback status.
     *
     * @return PingStatus
     */
    public function getPingStatus(): PingStatus
    {
        /** @var HasCorePost $this */
        return PingStatus::from($this->corePost()->getPingStatus());
    }

    /**
     * Set the post's pingback status.
     *
     * @param PingStatus|string $status
     * @return static
     */
    public function setPingStatus(PingStatus|string $status): static
    {
        $value = $status instanceof PingStatus ? $status->value : $status;

        /** @var HasCorePost $this */
        $this->corePost()->setPingStatus($value);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the pinged URLs.
     *
     * @return string[]
     */
    public function getPingedUrls(): array
    {
        /** @var HasCorePost $this */
        return explode("\n", $this->corePost()->getPinged());
    }

    /**
     * Set the pinged URLs.
     *
     * @param string[] $urls
     * @return static
     */
    public function setPingedUrls(array $urls): static
    {
        /** @var HasCorePost $this */
        $this->corePost()->setPinged(implode("\n", $urls));

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the URLs to ping.
     *
     * @return string[]
     */
    public function getUrlsToPing(): array
    {
        /** @var HasCorePost $this */
        return explode("\n", $this->corePost()->getToPing());
    }

    /**
     * Set the URLs to ping.
     *
     * @param string[] $urls
     * @return static
     */
    public function setUrlsToPing(array $urls): static
    {
        /** @var HasCorePost $this */
        $this->corePost()->setToPing(implode("\n", $urls));

        return $this;
    }
}