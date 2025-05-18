<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Proxy\HasProxyPost;
use Charm\Utilities\DateTime;

/**
 * Adds the updated date to a post model.
 *
 * Table: `wp_posts`
 * Column: `post_modified_gmt`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithUpdatedAt
{
    /**
     * Get the date and time the post was updated.
     *
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        /** @var HasProxyPost $this */
        return DateTime::init($this->proxyPost()->getPostModifiedGmt());
    }

    /**
     * Set the date and time the post was updated.
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