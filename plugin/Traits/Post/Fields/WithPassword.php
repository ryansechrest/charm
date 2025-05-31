<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Core\HasCorePost;

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
        /** @var HasCorePost $this */
        return $this->corePost()->getPostPassword();
    }

    /**
     * Set the post's password.
     *
     * @param string $password
     * @return static
     */
    public function setPassword(string $password): static
    {
        /** @var HasCorePost $this */
        $this->corePost()->setPostPassword($password);

        return $this;
    }
}