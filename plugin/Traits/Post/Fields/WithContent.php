<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Core\HasCorePost;

/**
 * Adds the content to a post model.
 *
 * Table: `wp_posts`
 * Column: `post_content`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithContent
{
    /**
     * Get the posts's content.
     *
     * @return string
     */
    public function getContent(): string
    {
        /** @var HasCorePost $this */
        return $this->corePost()->getPostContent();
    }

    /**
     * Set the post's content.
     *
     * @param string $content
     * @return static
     */
    public function setContent(string $content): static
    {
        /** @var HasCorePost $this */
        $this->corePost()->setPostContent($content);

        return $this;
    }
}