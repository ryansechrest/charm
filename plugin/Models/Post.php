<?php

namespace Charm\Models;

use Charm\Traits\Post\Fields\WithComments;
use Charm\Traits\Post\Fields\WithContent;
use Charm\Traits\Post\Fields\WithCreatedAt;
use Charm\Traits\Post\Fields\WithUser;
use Charm\Traits\Post\Fields\WithExcerpt;
use Charm\Traits\Post\Fields\WithPassword;
use Charm\Traits\Post\Fields\WithPingbacks;
use Charm\Traits\Post\Fields\WithSlug;
use Charm\Traits\Post\Fields\WithStatus;
use Charm\Traits\Post\Fields\WithTitle;
use Charm\Traits\Post\Fields\WithUpdatedAt;
use Charm\Traits\Post\Metas\WithThumbnail;
use Charm\Traits\Post\WithPermalink;

/**
 * Represents a blog post in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Post extends Base\Post
{
    // --- Post Fields ---------------------------------------------------------

    use WithUser;
    use WithCreatedAt;
    use WithUpdatedAt;

    use WithTitle;
    use WithSlug;
    use WithContent;
    use WithExcerpt;

    use WithStatus;
    use WithPassword;
    use WithPingbacks;
    use WithComments;

    // --- Post Metas ----------------------------------------------------------

    use WithThumbnail;

    // --- Post Helpers --------------------------------------------------------

    use WithPermalink;

    // *************************************************************************

    /**
     * Define post type
     */
    protected static function postType(): string
    {
        return 'post';
    }
}