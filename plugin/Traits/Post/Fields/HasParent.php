<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;
use Charm\Models\Base;
use Charm\Models\Post;

/**
 * Indicates that a post has a parent.
 *
 * Table: wp_posts
 * Column: post_parent
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasParent
{
    /**
     * Force parent class definition
     *
     * @return class-string<Base\Post>
     */
    abstract protected static function parentClass(): string;

    // *************************************************************************

    /**
     * Get parent
     *
     * @return ?Base\Post
     */
    public function getParent(): ?Base\Post
    {
        /** @var class-string<Base\Post> $parentClass */
        $parentClass = static::parentClass();

        /** @var HasWpPost $this */
        return $parentClass::init($this->wp()->getPostParent());
    }

    /**
     * Set parent
     *
     * @param Post|int|null $parent
     * @return static
     */
    public function setParent(Post|int|null $parent): static
    {
        $id = $parent instanceof Post ? $parent->wp()->getId() : $parent;

        /** @var HasWpPost $this */
        $this->wp()->setPostParent($id ?? 0);

        return $this;
    }
}