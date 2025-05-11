<?php

namespace Charm\Traits\Term\Fields;

use Charm\Contracts\HasWpTerm;

/**
 * Adds name to term model.
 *
 * Table: wp_terms
 * Column: name
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithName
{
    /**
     * Get term name
     *
     * @return string
     */
    public function getName(): string
    {
        /** @var HasWpTerm $this */
        return $this->wp()->getName();
    }

    /**
     * Set term name
     *
     * @param string $name
     * @return static
     */
    public function setName(string $name): static
    {
        /** @var HasWpTerm $this */
        $this->wp()->setName($name);

        return $this;
    }
}