<?php

namespace Charm\Contracts\Core;

use Charm\Models\Core;

/**
 * Ensures that the model implements a `Core\Post` instance.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasCorePost
{
    /**
     * Provides access to the `Core\Post` instance.
     *
     * @return ?Core\Post
     */
    public function corePost(): ?Core\Post;
}