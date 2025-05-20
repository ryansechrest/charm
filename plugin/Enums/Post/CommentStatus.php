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
    // The post is open for comments
    case Open = 'open';

    // The post is closed for comments
    case Closed = 'closed';
}