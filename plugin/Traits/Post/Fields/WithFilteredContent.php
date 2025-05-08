<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;

/**
 * Adds filtered content to post model.
 *
 * Table: wp_posts
 * Column: post_content_filtered
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithFilteredContent
{
    /**
     * Get filtered content
     *
     * @return string
     */
    public function getFilteredContent(): string
    {
        /** @var HasWpPost $this */
        return $this->wp()->getPostContentFiltered();
    }

    /**
     * Set filtered content
     *
     * @param string $content
     * @return static
     */
    public function setFilteredContent(string $content): static
    {
        /** @var HasWpPost $this */
        $this->wp()->setPostContentFiltered($content);

        return $this;
    }
}