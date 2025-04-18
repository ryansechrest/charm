<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;
use Charm\Models\User;

/**
 * Indicates that a post has a creator.
 *
 * Table: wp_posts
 * Column: post_author
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasCreator
{
    /**
     * Get creator
     *
     * @return User|null
     */
    public function getCreator(): ?User
    {
        /** @var HasWpPost $this */
        return User::init($this->wp()->getPostAuthor());
    }

    /**
     * Set creator
     *
     * @param User|int|null $user
     * @return static
     */
    public function setCreator(User|int|null $user): static
    {
        $id = $user instanceof User ? $user->getId() : $user;

        /** @var HasWpPost $this */
        $this->wp()->setPostAuthor($id ?? 0);

        return $this;
    }
}