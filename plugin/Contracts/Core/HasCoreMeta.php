<?php

namespace Charm\Contracts\Core;

use Charm\Models\Core;

/**
 * Ensures that the model implements a `Core\Meta` instance.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasCoreMeta
{
    /**
     * Provides access to the `Core\Meta` instance.
     *
     * @return ?Core\Meta
     */
    public function coreMeta(): ?Core\Meta;
}