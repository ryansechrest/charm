<?php

namespace Charm\Traits\Term\Fields;

use Charm\Contracts\Proxy\HasProxyTerm;
use Charm\Models\Base;

/**
 * Adds parent to term model.
 *
 * Table: wp_term_taxonomy
 * Column: parent
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithParent
{
    /**
     * Get parent
     *
     * @return ?Base\Term
     */
    public function getParent(): ?Base\Term
    {
        /** @var HasProxyTerm $this */
        return static::init($this->proxyTerm()->getParent());
    }

    /**
     * Set parent
     *
     * @param Base\Term|int|null $parent
     * @return static
     */
    public function setParent(Base\Term|int|null $parent): static
    {
        $id = $parent instanceof Base\Term
            ? $parent->proxyTerm()->getTermTaxonomyId() : $parent;

        /** @var HasProxyTerm $this */
        $this->proxyTerm()->setParent($id ?? 0);

        return $this;
    }
}