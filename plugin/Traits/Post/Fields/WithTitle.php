<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;

/**
 * Indicates that a post has a title.
 *
 * Table: wp_posts
 * Column: post_title
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithTitle
{
    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        /** @var HasWpPost $this */
        return $this->wp()->getPostTitle();
    }

    /**
     * Set title
     *
     * @param string $title
     * @return static
     */
    public function setTitle(string $title): static
    {
        /** @var HasWpPost $this */
        $this->wp()->setPostTitle($title);

        return $this;
    }
}