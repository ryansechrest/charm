<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;

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
        /** @var HasWpPost $this */
        return $this->wp()->getPostPassword();
    }

    /**
     * Set password
     *
     * @param string $password
     * @return static
     */
    public function setPassword(string $password): static
    {
        /** @var HasWpPost $this */
        $this->wp()->setPostPassword($password);

        return $this;
    }
}