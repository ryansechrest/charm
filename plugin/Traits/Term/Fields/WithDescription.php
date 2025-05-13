<?php

namespace Charm\Traits\Term\Fields;

use Charm\Contracts\Proxy\HasProxyTerm;

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
        /** @var HasProxyTerm $this */
        return $this->proxyTerm()->getDescription();
    }

    /**
     * Set term description
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