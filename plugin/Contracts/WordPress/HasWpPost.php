<?php

namespace Charm\Contracts\WordPress;

use WP_Post;

/**
 * Ensures that the model implements a `WP_Post` instance.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasWpPost
{
    /**
     * Provides access to the `WP_Post` instance.
     *
     * @return ?WP_Post
     */
    public function wpPost(): ?WP_Post;
}