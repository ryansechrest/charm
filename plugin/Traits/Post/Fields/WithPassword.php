<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Proxy\HasProxyPost;

/**
 * Adds password to post model.
 *
 * Table: wp_posts
 * Column: post_password
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithPassword
{
    /**
     * Get password
     *
     * @return string
     */
    public function getPassword(): string
    {
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getPostPassword();
    }

    /**
     * Set password
     *
     * @param string $password
     * @return static
     */
    public function setPassword(string $password): static
    {
        /** @var HasProxyPost $this */
        $this->proxyPost()->setPostPassword($password);

        return $this;
    }
}