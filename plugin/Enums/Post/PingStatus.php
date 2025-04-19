<?php

namespace Charm\Enums\Post;

/**
 * Indicates the ping status on a post.
 *
 * Table: wp_posts
 * Column: ping_status
 *
 * @author Ryan Sechrest
 * @package Charm
 */
enum PingStatus: string
{
    // Post is open for pings
    case OPEN = 'open';

    // Post is closed for pings
    case CLOSED = 'closed';
}