<?php

namespace Charm\Contracts\WordPress;

use WP_Term;

/**
 * Ensures that the model implements a `WP_Term` instance.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasWpTerm
{
    /**
     * Provides access to the `WP_Term` instance.
     *
     * @return ?WP_Term
     */
    public function wpTerm(): ?WP_Term;
}