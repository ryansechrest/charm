<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;
use Charm\Utilities\DateTime;
use DateTimeInterface;

/**
 * Indicates that a post has an updated date.
 *
 * Table: wp_posts
 * Column: post_modified_gmt
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasUpdatedAt
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
     * @param DateTimeInterface|string $dateTime
     * @return static
     */
    public function setUpdatedAt(DateTimeInterface|string $dateTime): static
    {
        $value = $dateTime instanceof DateTimeInterface
            ? $dateTime->format('Y-m-d H:i:s')
            : $dateTime;

        /** @var HasWpPost $this */
        $this->wp()->setPostModifiedGmt($value);

        return $this;
    }
}