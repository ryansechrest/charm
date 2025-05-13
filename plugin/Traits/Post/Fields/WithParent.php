<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Proxy\HasProxyPost;
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

        /** @var HasProxyPost $this */
        return $parentClass::init($this->proxyPost()->getPostParent());
    }

    /**
     * Set parent
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