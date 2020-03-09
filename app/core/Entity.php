<?php

namespace Charm\App\Core;

use Charm\App\Blueprint\Entity as EntityBlueprint;
use Charm\App\Feature\Cast;
use Charm\App\Feature\LoadProperties;

/**
 * Class Entity
 *
 * @author Ryan Sechrest
 * @package Charm\App\Core
 */
abstract class Entity implements EntityBlueprint
{
    use Cast, LoadProperties;

    /**
     * Initialize object
     *
     * @param int|null|self|string $key
     * @return null|self
     */
    abstract public static function init($key);
}