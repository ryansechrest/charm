<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Proxy\HasProxyPost;
use Charm\Utilities\DateTime;

/**
 * Adds created date to post model.
 *
 * Table: wp_posts
 * Column: post_date_gmt
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithCreatedAt
{
    /**
     * Get created date
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        /** @var HasProxyPost $this */
        return DateTime::init($this->proxyPost()->getPostDateGmt());
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

        /** @var HasProxyPost $this */
        $this->proxyPost()->setPostDateGmt($value);

        return $this;
    }
}