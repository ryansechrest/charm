<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Core\HasCorePost;

/**
 * Adds the title to a post model.
 *
 * Table: `wp_posts`
 * Column: `post_title`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithTitle
{
    /**
     * Get the post's title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        /** @var HasCorePost $this */
        return $this->corePost()->getPostTitle();
    }

    /**
     * Set the post's title.
     *
     * @param string $title
     * @return static
     */
    public function setTitle(string $title): static
    {
        /** @var HasCorePost $this */
        $this->corePost()->setPostTitle($title);

        return $this;
    }
}