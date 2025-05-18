<?php

namespace Charm\Traits\Term\Fields;

use Charm\Contracts\Proxy\HasProxyTerm;

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
        /** @var HasProxyTerm $this */
        return $this->proxyTerm()->getDescription();
    }

    /**
     * Set the term's description.
     *
     * @param string $description
     * @return static
     */
    public function setDescription(string $description): static
    {
        /** @var HasProxyTerm $this */
        $this->proxyTerm()->setDescription($description);

        return $this;
    }
}