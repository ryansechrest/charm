<?php

namespace Charm\Models;

use Charm\Traits\Post\Fields\HasComments;
use Charm\Traits\Post\Fields\HasContent;
use Charm\Traits\Post\Fields\HasCreatedAt;
use Charm\Traits\Post\Fields\HasCreator;
use Charm\Traits\Post\Fields\HasExcerpt;
use Charm\Traits\Post\Fields\HasId;
use Charm\Traits\Post\Fields\HasPassword;
use Charm\Traits\Post\Fields\HasPingbacks;
use Charm\Traits\Post\Fields\HasSlug;
use Charm\Traits\Post\Fields\HasStatus;
use Charm\Traits\Post\Fields\HasTitle;
use Charm\Traits\Post\Fields\HasUpdatedAt;
use Charm\Traits\Post\HasPermalink;

/**
 * Represents a blog post in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Post extends Base\Post
{
    use HasId;
    use HasCreator;
    use HasCreatedAt;
    use HasUpdatedAt;

    use HasTitle;
    use HasSlug;
    use HasContent;
    use HasExcerpt;

    use HasStatus;
    use HasPassword;
    use HasPingbacks;
    use HasComments;

    use HasPermalink;

    /**************************************************************************/

    /**
     * Post type
     */
    protected const POST_TYPE = 'post';
}