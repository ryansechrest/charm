<?php

namespace Charm\Enums\Post;

/**
 * Indicates the ping status on a post.
 *
 * Table: `wp_posts`
 * Column: `ping_status`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
enum PingStatus: string
{
    // The post is open for pings
    case Open = 'open';

    // The post is closed for pings
    case Closed = 'closed';
}