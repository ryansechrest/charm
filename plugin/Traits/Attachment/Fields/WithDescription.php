<?php

namespace Charm\Traits\Attachment\Fields;

use Charm\Contracts\HasWpPost;

/**
 * Adds description to attachment model.
 *
 * Table: wp_posts
 * Column: post_content
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithDescription
{
    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string
    {
        /** @var HasWpPost $this */
        return $this->wp()->getPostContent();
    }

    /**
     * Set description
     *
     * @param string $description
     * @return static
     */
    public function setDescription(string $description): static
    {
        /** @var HasWpPost $this */
        $this->wp()->setPostContent($description);

        return $this;
    }
}