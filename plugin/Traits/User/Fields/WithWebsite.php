<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\Proxy\HasProxyUser;

/**
 * Adds website to user model.
 *
 * Table: wp_users
 * Column: user_url
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithWebsite
{
    /**
     * Get website URL
     *
     * @return string|null
     */
    public function getWebsite(): ?string
    {
        /** @var HasProxyUser $this */
        return $this->proxyUser()->getUserUrl();
    }

    /**
     * Set website URL
     *
     * @param string $url
     * @return static
     */
    public function setWebsite(string $url): static
    {
        /** @var HasProxyUser $this */
        $this->proxyUser()->setUserUrl($url);

        return $this;
    }
}