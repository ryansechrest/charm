<?php

namespace Charm\Models\Taxonomy;

use Charm\Models\Base;
use Charm\Traits\Term\Fields\WithCount;
use Charm\Traits\Term\Fields\WithDescription;
use Charm\Traits\Term\Fields\WithName;
use Charm\Traits\Term\Fields\WithParent;
use Charm\Traits\Term\Fields\WithSlug;

/**
 * Represents a category in WordPress.
 *
 * @package Charm
 */
class Category extends Base\Term
{
    // --- Category Fields -----------------------------------------------------

    use WithName;
    use WithSlug;
    use WithDescription;

    use WithParent;
    use WithCount;

    // *************************************************************************

    /**
     * Define taxonomy
     */
    protected static function taxonomy(): string
    {
        return 'category';
    }
}
