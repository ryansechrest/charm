<?php

namespace Charm\Models\Meta;

use Charm\Models\Base;

/**
 * Represents a term meta in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class TermMeta extends Base\Meta
{
    /**
     * Define meta type
     */
    protected static function metaType(): string
    {
        return 'term';
    }
}