<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;
use Charm\Utilities\DateTime;
use DateTimeInterface;

/**
 * Indicates that a post has a created date.
 *
 * Table: wp_posts
 * Column: post_created_gmt
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
     * @param DateTimeInterface $dateTime
     * @return static
     */
    public function setCreatedAt(DateTimeInterface $dateTime): static
    {
        /** @var HasWpPost $this */
        $this->wp()->setPostDateGmt(
            $dateTime->format('Y-m-d H:i:s')
        );

        return $this;
    }
}