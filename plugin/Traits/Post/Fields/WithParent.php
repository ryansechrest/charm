<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Proxy\HasProxyPost;
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

        /** @var HasProxyPost $this */
        return $parentClass::init($this->proxyPost()->getPostParent());
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
            ? $parent->proxyPost()->getId() : $parent;

        /** @var HasProxyPost $this */
        $this->proxyPost()->setPostParent($id ?? 0);

        return $this;
    }
}