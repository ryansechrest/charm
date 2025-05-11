<?php

namespace Charm\Traits\Term\Fields;

use Charm\Contracts\HasWpTerm;

/**
 * Adds total number of terms to term model.
 *
 * Table: wp_term_taxonomy
 * Column: count
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithCount
{
    /**
     * Get total number of terms
     *
     * @return string
     */
    public function getCount(): string
    {
        /** @var HasWpTerm $this */
        return $this->wp()->getCount();
    }
}