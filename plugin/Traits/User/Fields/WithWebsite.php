<?php

namespace Charm\Traits\User\Fields;

use Charm\Contracts\Core\HasCoreUser;

/**
 * Adds the website to a user model.
 *
 * Table: `wp_users`
 * Column: `user_url`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithWebsite
{
    /**
     * Get the user's website URL.
     *
     * @return string|null
     */
    public function getWebsite(): ?string
    {
        /** @var HasCoreUser $this */
        return $this->coreUser()->getUserUrl();
    }

    /**
     * Set the user's website URL.
     *
     * @param string $url
     * @return static
     */
    public function setWebsite(string $url): static
    {
        /** @var HasCoreUser $this */
        $this->coreUser()->setUserUrl($url);

        return $this;
    }
}