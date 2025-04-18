<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;
use Charm\Models\User;

/**
 * Indicates that a post can have a user.
 *
 * Table: wp_posts
 * Column: post_author
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasCreatedBy
{
    /**
     * Get created by user
     *
     * @return User|null
     */
    public function getCreatedBy(): ?User
    {
        /** @var HasWpPost $this */
        return User::init($this->wp()->getPostAuthor());
    }

    /**
     * Set created by user
     *
     * @param User|int|null $user
     * @return static
     */
    public function setCreatedBy(User|int|null $user): static
    {
        $id = $user instanceof User ? $user->getId() : $user;

        /** @var HasWpPost $this */
        $this->wp()->setPostAuthor($id ?? 0);

        return $this;
    }
}