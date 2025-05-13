<?php

namespace Charm\Models;

use Charm\Traits\Post\Fields;
use Charm\Traits\Post\WithPermalink;

/**
 * Represents a page in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Page extends Base\Post
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
    use Fields\WithMenuOrder;

    use Fields\WithParent;

    // --- Post Helpers --------------------------------------------------------

    use WithPermalink;

    // *************************************************************************

    /**
     * Define post type
     */
    protected static function postType(): string
    {
        return 'page';
    }

    // -------------------------------------------------------------------------

    /**
     * Define parent class
     *
     * @return string
     */
    protected static function parentClass(): string
    {
        return Page::class;
    }
}