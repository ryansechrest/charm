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
    // Post is published
    case PUBLISHED = 'publish';

    // Post is scheduled to be published
    case SCHEDULED = 'future';

    // Post is in draft and only viewable by authorized users
    case DRAFT = 'draft';

    // Post is pending to be published by an authorized user
    case PENDING = 'pending';

    // Post is private and only viewable by an administrator
    case PRIVATE = 'private';

    // Post is trashed
    case TRASHED = 'trash';

    // Post is a revision that was automatically created by WordPress
    case REVISION = 'auto-draft';

    // Post is inheriting its status from its parent
    case INHERIT = 'inherit';
}