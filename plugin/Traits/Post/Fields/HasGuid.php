<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;

/**
 * Indicates post has GUID.
 *
 * Table: wp_posts
 * Column: guid
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasGuid
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