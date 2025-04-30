<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;
use Charm\Utilities\DateTime;

/**
 * Indicates that a post has an updated date.
 *
 * Table: wp_posts
 * Column: post_modified_gmt
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithUpdatedAt
{
    /**
     * Get updated date
     *
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        /** @var HasWpPost $this */
        return DateTime::init($this->wp()->getPostModifiedGmt());
    }

    /**
     * Set updated date
     *
     * @param DateTime|string $dateTime
     * @return static
     */
    public function setUpdatedAt(DateTime|string $dateTime): static
    {
        $value = $dateTime instanceof DateTime
            ? $dateTime->formatForDb()
            : $dateTime;

        /** @var HasWpPost $this */
        $this->wp()->setPostModifiedGmt($value);

        return $this;
    }
}