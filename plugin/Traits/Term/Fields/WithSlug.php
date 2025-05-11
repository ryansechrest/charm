<?php

namespace Charm\Traits\Term\Fields;

use Charm\Contracts\HasWpTerm;

/**
 * Adds slug to term model.
 *
 * Table: wp_terms
 * Column: slug
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithSlug
{
    /**
     * Get term slug
     *
     * @return string
     */
    public function getSlug(): string
    {
        /** @var HasWpTerm $this */
        return $this->wp()->getSlug();
    }

    /**
     * Set term slug
     *
     * @param string $slug
     * @return static
     */
    public function setSlug(string $slug): static
    {
        /** @var HasWpTerm $this */
        $this->wp()->setSlug($slug);

        return $this;
    }
}