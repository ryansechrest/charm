<?php

namespace Charm\Traits\Term\Fields;

use Charm\Contracts\Proxy\HasProxyTerm;

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
        /** @var HasProxyTerm $this */
        return $this->proxyTerm()->getName();
    }

    /**
     * Set term name
     *
     * @param string $name
     * @return static
     */
    public function setName(string $name): static
    {
        /** @var HasProxyTerm $this */
        $this->proxyTerm()->setName($name);

        return $this;
    }
}