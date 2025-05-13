<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasProxyPost;

/**
 * Adds GUID to post model.
 *
 * Table: wp_posts
 * Column: guid
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithGuid
{
    /**
     * Get GUID
     *
     * @return string
     */
    public function getGuid(): string
    {
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getGuid();
    }
}