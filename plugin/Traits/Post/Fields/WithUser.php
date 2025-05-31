<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Core\HasCorePost;
use Charm\Models\Base;
use Charm\Models\User;

/**
 * Adds the user to a post model.
 *
 * Table: `wp_posts`
 * Column: `post_author`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithUser
{
    /**
     * Default user class definition.
     *
     * @return class-string<Base\User>
     */
    protected static function userClass(): string
    {
        return User::class;
    }

    // *************************************************************************

    /**
     * Get the user that owns the post.
     *
     * @return ?Base\User
     */
    public function getUser(): ?Base\User
    {
        /** @var class-string<Base\User> $userClass */
        $userClass = static::userClass();

        /** @var HasCorePost $this */
        return $userClass::init($this->corePost()->getPostAuthor());
    }

    /**
     * Set the user that owns the post.
     *
     * @param User|int|null $user
     * @return static
     */
    public function setUser(User|int|null $user): static
    {
        $id = $user instanceof User ? $user->getId() : $user;

        /** @var HasCorePost $this */
        $this->corePost()->setPostAuthor($id ?? 0);

        return $this;
    }
}