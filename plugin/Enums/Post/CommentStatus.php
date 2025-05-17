<?php

namespace Charm\Enums\Post;

/**
 * Indicates the comment status on a post.
 *
 * Table: `wp_posts`
 * Column: `comment_status`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
enum CommentStatus: string
{
    // Post is open for comments
    case OPEN = 'open';

    // Post is closed for comments
    case CLOSED = 'closed';
}