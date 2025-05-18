<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Proxy\HasProxyPost;
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
        /** @var HasProxyPost $this */
        return PingStatus::from($this->proxyPost()->getPingStatus());
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

        /** @var HasProxyPost $this */
        $this->proxyPost()->setPingStatus($value);

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
        /** @var HasProxyPost $this */
        return explode("\n", $this->proxyPost()->getPinged());
    }

    /**
     * Set the pinged URLs.
     *
     * @param string[] $urls
     * @return static
     */
    public function setPingedUrls(array $urls): static
    {
        /** @var HasProxyPost $this */
        $this->proxyPost()->setPinged(implode("\n", $urls));

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
        /** @var HasProxyPost $this */
        return explode("\n", $this->proxyPost()->getToPing());
    }

    /**
     * Set the URLs to ping.
     *
     * @param string[] $urls
     * @return static
     */
    public function setUrlsToPing(array $urls): static
    {
        /** @var HasProxyPost $this */
        $this->proxyPost()->setToPing(implode("\n", $urls));

        return $this;
    }
}