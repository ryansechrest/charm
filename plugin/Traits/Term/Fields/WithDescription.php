<?php

namespace Charm\Traits\Term\Fields;

use Charm\Contracts\Core\HasCoreTerm;

/**
 * Adds the description to a term model.
 *
 * Table: `wp_term_taxonomy`
 * Column: `description`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithDescription
{
    /**
     * Get the term's description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        /** @var HasCoreTerm $this */
        return $this->coreTerm()->getDescription();
    }

    /**
     * Set the term's description.
     *
     * @param string $description
     * @return static
     */
    public function setDescription(string $description): static
    {
        /** @var HasCoreTerm $this */
        $this->coreTerm()->setDescription($description);

        return $this;
    }
}