<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;

/**
 * Indicates post has content.
 *
 * Table: wp_posts
 * Column: post_content
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasContent
{
    /**
     * Get content
     *
     * @return string
     */
    public function getContent(): string
    {
        /** @var HasWpPost $this */
        return $this->wp()->getPostContent();
    }

    /**
     * Set content
     *
     * @param string $content
     * @return static
     */
    public function setContent(string $content): static
    {
        /** @var HasWpPost $this */
        $this->wp()->setPostContent($content);

        return $this;
    }
}