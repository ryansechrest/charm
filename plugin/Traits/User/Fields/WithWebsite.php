<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\HasWpUser;

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
        /** @var HasWpUser $this */
        return $this->wp()->getUserUrl();
    }

    /**
     * Set website URL
     *
     * @param string $url
     * @return static
     */
    public function setWebsite(string $url): static
    {
        /** @var HasWpUser $this */
        $this->wp()->setUserUrl($url);

        return $this;
    }
}