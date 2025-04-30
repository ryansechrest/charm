<?php

namespace Charm\Models;

use Charm\Traits\Post\Fields\WithContent;
use Charm\Traits\Post\Fields\WithCreatedAt;
use Charm\Traits\Post\Fields\WithUser;
use Charm\Traits\Post\Fields\WithExcerpt;
use Charm\Traits\Post\Fields\WithMenuOrder;
use Charm\Traits\Post\Fields\WithParent;
use Charm\Traits\Post\Fields\WithPassword;
use Charm\Traits\Post\Fields\WithSlug;
use Charm\Traits\Post\Fields\WithStatus;
use Charm\Traits\Post\Fields\WithTitle;
use Charm\Traits\Post\Fields\WithUpdatedAt;
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

    use WithUser;
    use WithCreatedAt;
    use WithUpdatedAt;

    use WithTitle;
    use WithSlug;
    use WithContent;
    use WithExcerpt;

    use WithStatus;
    use WithPassword;
    use WithMenuOrder;

    use WithParent;

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