<?php

namespace Charm\Models\Metas;

use Charm\Models\Base;

/**
 * Represents a user meta in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class UserMeta extends Base\Meta
{
    /**
     * Set the meta type.
     */
    protected static function metaType(): string
    {
        return 'user';
    }
}
