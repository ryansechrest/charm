<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Proxy\HasProxyPost;

/**
 * Adds the GUID to a post model.
 *
 * Table: `wp_posts`
 * Column: `guid`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithGuid
{
    /**
     * Get the post's GUID.
     *
     * @return string
     */
    public function getGuid(): string
    {
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getGuid();
    }
}