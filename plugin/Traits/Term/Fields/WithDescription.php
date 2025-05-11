<?php

namespace Charm\Traits\Term\Fields;

use Charm\Contracts\HasWpTerm;

/**
 * Adds description to term model.
 *
 * Table: wp_term_taxonomy
 * Column: description
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithDescription
{
    /**
     * Get term description
     *
     * @return string
     */
    public function getDescription(): string
    {
        /** @var HasWpTerm $this */
        return $this->wp()->getDescription();
    }

    /**
     * Set term description
     *
     * @param string $description
     * @return static
     */
    public function setDescription(string $description): static
    {
        /** @var HasWpTerm $this */
        $this->wp()->setDescription($description);

        return $this;
    }
}