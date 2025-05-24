<?php

namespace Charm\Traits\Term\Fields;

use Charm\Contracts\Proxy\HasProxyTerm;
use Charm\Models\Base;

/**
 * Adds the parent to a term model.
 *
 * Table: `wp_term_taxonomy`
 * Column: `parent`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithParent
{
    /**
     * Get the term's parent.
     *
     * @return ?Base\Term
     */
    public function getParent(): ?Base\Term
    {
        /** @var HasProxyTerm $this */
        return static::init($this->proxyTerm()->getParent());
    }

    /**
     * Set the term's parent.
     *
     * @param Base\Term|int|null $parent
     * @return static
     */
    public function setParent(Base\Term|int|null $parent): static
    {
        $id = $parent instanceof Base\Term ? $parent->getId() : $parent;

        /** @var HasProxyTerm $this */
        $this->proxyTerm()->setParent($id ?? 0);

        return $this;
    }
}