<?php

namespace Charm\Contracts;

use Charm\Models\WordPress\Term;

/**
 * Ensures that the model has a WordPress meta.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasWpTerm
{
    /**
     * Get term
     *
     * @return ?Term
     */
    public function wp(): ?Term;
}
