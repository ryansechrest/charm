<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;
use Charm\Models\Base;
use Charm\Models\User;

/**
 * Adds user to post model.
 *
 * Table: wp_posts
 * Column: post_author
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithUser
{
    /**
     * Default user class definition
     *
     * @return class-string<Base\User>
     */
    protected static function userClass(): string
    {
        return User::class;
    }

    // *************************************************************************

    /**
     * Get user
     *
     * @return ?Base\User
     */
    public function getUser(): ?Base\User
    {
        /** @var class-string<Base\User> $userClass */
        $userClass = static::userClass();

        /** @var HasWpPost $this */
        return $userClass::init($this->wp()->getPostAuthor());
    }

    /**
     * Set user
     *
     * @param User|int|null $user
     * @return static
     */
    public function setUser(User|int|null $user): static
    {
        $id = $user instanceof User ? $user->getId() : $user;

        /** @var HasWpPost $this */
        $this->wp()->setPostAuthor($id ?? 0);

        return $this;
    }
}