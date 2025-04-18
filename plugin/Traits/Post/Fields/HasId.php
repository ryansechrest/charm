<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;

/**
 * Indicates that a post has an ID.
 *
 * Table: wp_posts
 * Column: ID
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasId
{
    /**
     * Get ID
     *
     * @return int
     */
    public function getId(): int
    {
        /** @var HasWpPost $this */
        return $this->wp()->getId();
    }
}