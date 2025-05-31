<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Core\HasCorePost;
use Charm\Models\Base;

/**
 * Adds the parent to a post model.
 *
 * Table: `wp_posts`
 * Column: `post_parent`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithParent
{
    /**
     * Ensures that parent class is defined.
     *
     * @return class-string<Base\Post>
     */
    abstract protected static function parentClass(): string;

    // *************************************************************************

    /**
     * Get the post's parent.
     *
     * @return ?Base\Post
     */
    public function getParent(): ?Base\Post
    {
        /** @var class-string<Base\Post> $parentClass */
        $parentClass = static::parentClass();

        /** @var HasCorePost $this */
        return $parentClass::init($this->corePost()->getPostParent());
    }

    /**
     * Set the post's parent.
     *
     * @param Base\Post|int|null $parent
     * @return static
     */
    public function setParent(Base\Post|int|null $parent): static
    {
        $id = $parent instanceof Base\Post
            ? $parent->corePost()->getId() : $parent;

        /** @var HasCorePost $this */
        $this->corePost()->setPostParent($id ?? 0);

        return $this;
    }
}