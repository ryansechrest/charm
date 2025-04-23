<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;
use Charm\Utilities\DateTime;

/**
 * Indicates that a post has a created date.
 *
 * Table: wp_posts
 * Column: post_date_gmt
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasCreatedAt
{
    /**
     * Get created date
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        /** @var HasWpPost $this */
        return DateTime::init($this->wp()->getPostDateGmt());
    }

    /**
     * Set created date
     *
     * @param DateTime|string $dateTime
     * @return static
     */
    public function setCreatedAt(DateTime|string $dateTime): static
    {
        $value = $dateTime instanceof DateTime
            ? $dateTime->formatForDb()
            : $dateTime;

        /** @var HasWpPost $this */
        $this->wp()->setPostDateGmt($value);

        return $this;
    }
}