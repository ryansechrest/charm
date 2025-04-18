<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;
use Charm\Models\BasePost;
use WP_Post;

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
     * Initialize post
     *
     * @param int|null|string|WP_Post $key
     * @return static|null
     */
    abstract public static function init(int|null|string|WP_Post $key = null): ?static;

    /**************************************************************************/

    /**
     * Get parent
     *
     * @return ?static
     */
    public function getParent(): ?static
    {
        /** @var HasWpPost $this */
        return static::init($this->wp()->getPostParent());
    }

    /**
     * Set parent
     *
     * @param BasePost|int|null $parent
     * @return static
     */
    public function setParent(BasePost|int|null $parent): static
    {
        $id = $parent instanceof BasePost ? $parent->wp()->getId() : $parent;

        /** @var HasWpPost $this */
        $this->wp()->setPostParent($id ?? 0);

        return $this;
    }
}