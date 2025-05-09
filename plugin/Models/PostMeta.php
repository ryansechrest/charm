<?php

namespace Charm\Models;

/**
 * Represents a post meta in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class PostMeta extends Base\Meta
{
    /**
     * Define meta type
     */
    protected static function metaType(): string
    {
        return 'post';
    }
}