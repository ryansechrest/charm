<?php

namespace Charm\Traits\Term\Fields;

use Charm\Contracts\Proxy\HasProxyTerm;

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
        /** @var HasProxyTerm $this */
        return $this->proxyTerm()->getSlug();
    }

    /**
     * Set term slug
     *
     * @param string $slug
     * @return static
     */
    public function setSlug(string $slug): static
    {
        /** @var HasProxyTerm $this */
        $this->proxyTerm()->setSlug($slug);

        return $this;
    }
}