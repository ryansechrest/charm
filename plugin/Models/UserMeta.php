<?php

namespace Charm\Models;

/**
 * Represents a user meta in WordPress.
 *
 * @package Charm
 */
class UserMeta extends Base\Meta
{
    /**
     * Define meta type
     */
    protected static function metaType(): string
    {
        return 'user';
    }
}
