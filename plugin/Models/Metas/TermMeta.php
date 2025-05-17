<?php

namespace Charm\Models\Metas;

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
     * Set the meta type.
     */
    protected static function metaType(): string
    {
        return 'term';
    }
}