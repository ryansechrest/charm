<?php

namespace Charm\Models\Taxonomies;

use Charm\Models\Base;
use Charm\Traits\Term\Fields\WithCount;
use Charm\Traits\Term\Fields\WithDescription;
use Charm\Traits\Term\Fields\WithName;
use Charm\Traits\Term\Fields\WithSlug;

/**
 * Represents a tag in WordPress.
 *
 * @package Charm
 */
class Tag extends Base\Term
{
    // --- Tag Fields ----------------------------------------------------------

    use WithName;
    use WithSlug;
    use WithDescription;

    use WithCount;

    // *************************************************************************

    /**
     * Define taxonomy
     */
    protected static function taxonomy(): string
    {
        return 'post_tag';
    }
}
