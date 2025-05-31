<?php

namespace Charm\Traits\Term\Fields;

use Charm\Contracts\Core\HasCoreTerm;

/**
 * Adds the total number of terms to a term model.
 *
 * Table: `wp_term_taxonomy`
 * Column: `count`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithCount
{
    /**
     * Get the total number of terms.
     *
     * @return string
     */
    public function getCount(): string
    {
        /** @var HasCoreTerm $this */
        return $this->coreTerm()->getCount();
    }
}