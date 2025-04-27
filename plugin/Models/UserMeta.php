<?php

namespace Charm\Models;

use Charm\Models\Base\Meta;

/**
 * Represents a user meta in WordPress.
 *
 * @package Charm
 */
class UserMeta extends Meta
{
    /**
     * Define meta type
     */
    protected static function metaType(): string
    {
        return 'user';
    }
}
