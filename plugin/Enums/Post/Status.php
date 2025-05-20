<?php

namespace Charm\Enums\Post;

/**
 * Indicates the status of a post.
 *
 * Table: `wp_posts`
 * Column: `post_status`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
enum Status: string
{
    // The post is published
    case Published = 'publish';

    // The post is scheduled to be published
    case Scheduled = 'future';

    // The post is in draft and only viewable by authorized users
    case Draft = 'draft';

    // The post is pending to be published by an authorized user
    case Pending = 'pending';

    // The post is private and only viewable by an administrator
    case Private = 'private';

    // The post is trashed
    case Trashed = 'trash';

    // The post is a revision automatically created by WordPress
    case Revision = 'auto-draft';

    // The post is inheriting its status from its parent
    case Inherit = 'inherit';
}