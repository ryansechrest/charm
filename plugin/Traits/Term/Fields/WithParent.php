<?php

namespace Charm\Traits\Term\Fields;

use Charm\Contracts\Core\HasCoreTerm;
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
        /** @var HasCoreTerm $this */
        return static::init($this->coreTerm()->getParent());
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

        /** @var HasCoreTerm $this */
        $this->coreTerm()->setParent($id ?? 0);

        return $this;
    }
}