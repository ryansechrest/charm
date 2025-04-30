<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;

/**
 * Indicates that a post has a GUID.
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
        /** @var HasWpPost $this */
        return $this->wp()->getGuid();
    }
}