<?php

namespace Charm\Models;

use Charm\Models\Base\Meta;

/**
 * Represents a post meta in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class PostMeta extends Meta
{
    /**
     * Define meta type
     */
    protected static function metaType(): string
    {
        return 'post';
    }
}