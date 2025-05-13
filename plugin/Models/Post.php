<?php

namespace Charm\Models;

use Charm\Traits\Post\Fields;
use Charm\Traits\Post\Metas;
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

    use Fields\WithUser;
    use Fields\WithCreatedAt;
    use Fields\WithUpdatedAt;

    use Fields\WithTitle;
    use Fields\WithSlug;
    use Fields\WithContent;
    use Fields\WithExcerpt;

    use Fields\WithStatus;
    use Fields\WithPassword;
    use Fields\WithPingbacks;
    use Fields\WithComments;

    // --- Post Metas ----------------------------------------------------------

    use Metas\WithThumbnail;

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