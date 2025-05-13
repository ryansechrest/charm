<?php

namespace Charm\Traits\Attachment\Fields;

use Charm\Contracts\Proxy\HasProxyPost;

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
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getPostContent();
    }

    /**
     * Set description
     *
     * @param string $description
     * @return static
     */
    public function setDescription(string $description): static
    {
        /** @var HasProxyPost $this */
        $this->proxyPost()->setPostContent($description);

        return $this;
    }
}