<?php

namespace Charm\Contracts;

use Charm\Models\Base\Term;
use Charm\Relationships\TermRelationship;
use Charm\Support\Result;

/**
 * Ensures that the model can manage terms.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasTerms
{
    /**
     * Gets the term relationship for the taxonomy.
     *
     * @param class-string<Term> $termClass
     * @return TermRelationship
     */
    function taxonomy(string $termClass): TermRelationship;

    /**
     * Persists the terms on the object in the taxonomy.
     *
     * @return Result[]
     */
    function persistTerms(): array;
}