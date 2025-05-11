<?php

namespace Charm\Models\Attachment;

use Charm\Models\Base;
use Charm\Traits\Attachment\Fields\WithAttachedTo;
use Charm\Traits\Attachment\Fields\WithCaption;
use Charm\Traits\Attachment\Fields\WithDescription;
use Charm\Traits\Attachment\Fields\WithMimeType;
use Charm\Traits\Attachment\Metas\WithAltText;
use Charm\Traits\Attachment\Metas\WithFilePath;
use Charm\Traits\Attachment\Metas\WithMetaData;
use Charm\Traits\Attachment\WithFileSize;
use Charm\Traits\Post\Fields\WithCreatedAt;
use Charm\Traits\Post\Fields\WithSlug;
use Charm\Traits\Post\Fields\WithTitle;
use Charm\Traits\Post\Fields\WithUpdatedAt;
use Charm\Traits\Post\Fields\WithUser;
use Charm\Traits\Post\WithPermalink;

/**
 * Represents an attachment in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Attachment extends Base\Post
{
    // --- Attachment Fields ---------------------------------------------------

    use WithUser;
    use WithCreatedAt;
    use WithUpdatedAt;

    use WithTitle;
    use WithSlug;
    use WithCaption;
    use WithDescription;

    use WithAttachedTo;
    use WithMimeType;

    // --- Attachment Metas ----------------------------------------------------

    use WithAltText;
    use WithFilePath;
    use WithMetaData;

    // --- Attachment Helpers --------------------------------------------------

    use WithFileSize;
    use WithPermalink;

    // *************************************************************************

    /**
     * Define post type
     */
    protected static function postType(): string
    {
        return 'attachment';
    }
}