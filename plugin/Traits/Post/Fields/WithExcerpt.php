<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Core\HasCorePost;

/**
 * Adds the excerpt to a post model.
 *
 * Table: `wp_posts`
 * Column: `post_excerpt`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithExcerpt
{
    /**
     * Get the post's excerpt.
     *
     * @return string
     */
    public function getExcerpt(): string
    {
        /** @var HasCorePost $this */
        return $this->corePost()->getPostExcerpt();
    }

    /**
     * Set the post's excerpt.
     *
     * @param string $excerpt
     * @return static
     */
    public function setExcerpt(string $excerpt): static
    {
        /** @var HasCorePost $this */
        $this->corePost()->setPostExcerpt($excerpt);

        return $this;
    }
}