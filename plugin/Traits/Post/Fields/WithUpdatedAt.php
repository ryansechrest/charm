<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasProxyPost;
use Charm\Utilities\DateTime;

/**
 * Adds updated date to post model.
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
        /** @var HasProxyPost $this */
        return DateTime::init($this->proxyPost()->getPostModifiedGmt());
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

        /** @var HasProxyPost $this */
        $this->proxyPost()->setPostModifiedGmt($value);

        return $this;
    }
}