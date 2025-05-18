<?php

namespace Charm\Models\Terms;

use Charm\Models\Base;
use Charm\Traits\Term\Fields;

/**
 * Represents a category term in WordPress.
 *
 * @package Charm
 */
class Category extends Base\Term
{
    // --- Category Fields -----------------------------------------------------

    use Fields\WithDescription;

    use Fields\WithParent;
    use Fields\WithCount;

    // *************************************************************************

    /**
     * Set the name of the taxonomy.
     */
    public static function taxonomy(): string
    {
        return 'category';
    }
}
