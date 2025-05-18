<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Proxy\HasProxyPost;
use Charm\Utilities\DateTime;

/**
 * Adds the created date to the post model.
 *
 * Table: `wp_posts`
 * Column: `post_date_gmt`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithCreatedAt
{
    /**
     * Get the date and time the post was created.
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        /** @var HasProxyPost $this */
        return DateTime::init($this->proxyPost()->getPostDateGmt());
    }

    /**
     * Set the date and time the post was created.
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