<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Core\HasCorePost;

/**
 * Adds the filtered content to a post model.
 *
 * Table: `wp_posts`
 * Column: `post_content_filtered`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithFilteredContent
{
    /**
     * Get the post's filtered content.
     *
     * @return string
     */
    public function getFilteredContent(): string
    {
        /** @var HasCorePost $this */
        return $this->corePost()->getPostContentFiltered();
    }

    /**
     * Set the post's filtered content.
     *
     * @param string $content
     * @return static
     */
    public function setFilteredContent(string $content): static
    {
        /** @var HasCorePost $this */
        $this->corePost()->setPostContentFiltered($content);

        return $this;
    }
}