<?php

namespace Charm\Traits;

use Charm\Contracts\HasDeferredCalls;
use Charm\Models\Base\Term;
use Charm\Relationships\TermRelationship;
use Charm\Support\Result;
use InvalidArgumentException;

/**
 * Adds support for managing terms on a model.
 *
 * A model, like `Post`, can be associated with terms from different taxonomies
 * (e.g., categories, tags). This trait provides methods to manage these
 * term relationships through a fluent interface.
 *
 * Term operations (add, remove, set) are tracked internally and do not
 * immediately persist in the database. Instead, changes are stored in the
 * `TermRelationship` class until explicitly saved with `persistTerms()`.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithTerms
{
    /**
     * List of the taxonomies the model uses.
     *
     * @var TermRelationship[]
     */
    protected array $taxonomies = [];

    // *************************************************************************

    /**
     * Returns the ID of the model.
     *
     * @return int
     */
    abstract public function getId(): int;

    // *************************************************************************

    /**
     * Get the term relationship for the taxonomy.
     *
     * @param class-string<Term> $termClass Category::class
     * @return TermRelationship
     */
    protected function taxonomy(string $termClass): TermRelationship
    {
        // If the $termClass is not a subclass of the Term::class,
        // abort with an exception to ensure compliance
        if (!is_subclass_of($termClass, Term::class)) {
            throw new InvalidArgumentException(
                'Term class must be subclass of ' . Term::class . '.'
            );
        }

        /** @var HasDeferredCalls $this */
        $this->registerDeferred(method: 'persistTerms');

        // If a term relationship doesn't exist for this taxonomy, create it
        if (!isset($this->taxonomies[$termClass])) {
            $this->taxonomies[$termClass] = new TermRelationship([
                'objectId' => $this->getId(),
                'termClass' => $termClass,
            ]);
        }

        return $this->taxonomies[$termClass];
    }

    /**
     * Persist the terms on the object in the taxonomy.
     *
     * @return Result[]
     */
    protected function persistTerms(): array
    {
        $results = [];

        foreach ($this->taxonomies as $termRelationship) {

            // It's possible that the model was new and didn't have an ID when
            // the terms were saved within the term relationship, so let's set
            // the model ID now to be safe
            $termRelationship->setObjectId($this->getId());

            // Merge results from each taxonomy's term relationship
            $results = array_merge(
                $results, $termRelationship->persistTerms()
            );
        }

        return $results;
    }
}