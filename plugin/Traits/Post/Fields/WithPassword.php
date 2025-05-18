<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Proxy\HasProxyPost;

/**
 * Adds the password to a post model.
 *
 * Table: `wp_posts`
 * Column: `post_password`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithPassword
{
    /**
     * Get the post's password.
     *
     * @return string
     */
    public function getPassword(): string
    {
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getPostPassword();
    }

    /**
     * Set the post's password.
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