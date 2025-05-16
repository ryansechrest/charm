<?php

namespace Charm\Models\Terms;

use Charm\Models\Base;
use Charm\Traits\Term\Fields;

/**
 * Represents a tag term in WordPress.
 *
 * @package Charm
 */
class Tag extends Base\Term
{
    // --- Tag Fields ----------------------------------------------------------

    use Fields\WithDescription;

    use Fields\WithCount;

    // *************************************************************************

    /**
     * Define taxonomy
     */
    protected static function taxonomy(): string
    {
        return 'post_tag';
    }
}
