<?php

namespace Charm\Models\Metas;

use Charm\Models\Base;

/**
 * Represents a post meta in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class PostMeta extends Base\Meta
{
    /**
     * Set the meta type.
     */
    protected static function metaType(): string
    {
        return 'post';
    }
}