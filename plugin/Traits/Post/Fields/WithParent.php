<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;
use Charm\Models\Base;

/**
 * Adds parent to post model.
 *
 * Table: wp_posts
 * Column: post_parent
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithParent
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
     * @param Base\Post|int|null $parent
     * @return static
     */
    public function setParent(Base\Post|int|null $parent): static
    {
        $id = $parent instanceof Base\Post ? $parent->wp()->getId() : $parent;

        /** @var HasWpPost $this */
        $this->wp()->setPostParent($id ?? 0);

        return $this;
    }
}