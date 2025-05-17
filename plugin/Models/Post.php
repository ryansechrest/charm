<?php

namespace Charm\Models;

use Charm\Traits\Post\Fields;
use Charm\Traits\Post\Metas;
use Charm\Traits\Post\WithPermalink;
use Charm\Traits\Taxonomy;

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

    // --- Taxonomies ----------------------------------------------------------

    use Taxonomy\WithCategories;
    use Taxonomy\WithTags;

    // *************************************************************************

    /**
     * Set the post type slug.
     */
    protected static function postType(): string
    {
        return 'post';
    }
}