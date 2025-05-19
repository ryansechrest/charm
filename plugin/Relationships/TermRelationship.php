<?php

namespace Charm\Relationships;

use Charm\Models\Base\Term;
use Charm\Support\Result;
use WP_Error;
use WP_Term;

/**
 * Represents the relationship between a model and its terms in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class TermRelationship
{
    /**
     * Object ID.
     *
     * @var ?int Post ID, User ID, etc.
     */
    protected ?int $objectId = null;

    /**
     * Term class.
     *
     * @var ?class-string<Term> Category::class, Tag::class, etc.
     */
    protected ?string $termClass = null;

    /**
     * Terms retrieved from the database.
     *
     * @var Term[]
     */
    protected array $getTerms = [];

    /**
     * Terms to add to the object.
     *
     * @var Term[]
     */
    protected array $addTerms = [];

    /**
     * Terms to remove from the object.
     *
     * @var Term[]
     */
    protected array $removeTerms = [];

    /**
     * Terms to set on the object.
     *
     * @var Term[]
     */
    protected array $setTerms = [];

    // *************************************************************************

    /**
     * TermRelationship constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    /**
     * Load the instance with data.
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['objectId'])) {
            $this->objectId = $data['objectId'];
        }

        if (isset($data['termClass'])) {
            $this->termClass = $data['termClass'];
        }
    }

    // *************************************************************************

    /**
     * Add the terms to an object (appends terms).
     *
     * $terms `int`    -> Term ID
     *        `string` -> Term Slug
     *        `array`  -> Term IDs/Slugs
     *
     * @param int $objectId 1
     * @param int|string|array $terms
     * @param string $taxonomy category
     * @return Result
     * @see wp_add_object_terms()
     */
    public static function addObjectTerms(
        int $objectId, int|string|array $terms, string $taxonomy
    ): Result
    {
        $result = wp_add_object_terms(
            object_id: $objectId, terms: $terms, taxonomy: $taxonomy
        );

        if ($result instanceof WP_Error) {
            return Result::wpError(wpError: $result);
        }

        if (!is_array($result)) {
            return Result::error(
                code: 'wp_add_object_terms_failed',
                message: __(
                    'wp_add_object_terms() did not return an array.', 'charm'
                )
            );
        }

        return Result::success();
    }

    /**
     * Remove the terms from an object.
     *
     * $terms `int`    -> Term ID
     *        `string` -> Term Slug
     *        `array`  -> Term IDs/Slugs
     *
     * @param int $objectId 1
     * @param int|string|array $terms
     * @param string $taxonomy category
     * @return Result
     * @see wp_remove_object_terms()
     */
    public static function removeObjectTerms(
        int $objectId, int|string|array $terms, string $taxonomy
    ): Result
    {
        $result = wp_remove_object_terms(
            object_id: $objectId, terms: $terms, taxonomy: $taxonomy
        );

        if ($result instanceof WP_Error) {
            return Result::wpError(wpError: $result);
        }

        if ($result !== true) {
            return Result::error(
                code: 'wp_remove_object_terms_failed',
                message: __(
                    'wp_remove_object_terms() did not return true.', 'charm'
                )
            );
        }

        return Result::success();
    }

    /**
     * Set terms on an object (replaces terms)
     *
     * $terms `int`    -> Term ID
     *        `string` -> Term Slug
     *        `array`  -> Term IDs/Slugs
     *
     * @param int $objectId 1
     * @param int|string|array $terms
     * @param string $taxonomy category
     * @return Result
     * @see wp_set_object_terms()
     */
    public static function setObjectTerms(
        int $objectId, int|string|array $terms, string $taxonomy
    ): Result
    {
        $result = wp_set_object_terms(
            object_id: $objectId, terms: $terms, taxonomy: $taxonomy
        );

        if ($result instanceof WP_Error) {
            return Result::wpError(wpError: $result);
        }

        if (!is_array($result)) {
            return Result::error(
                code: 'wp_set_object_terms_failed',
                message: __(
                    'wp_set_object_terms() did not return an array.', 'charm'
                )
            );
        }

        return Result::success();
    }

    // *************************************************************************

    /**
     * Get the terms from the object.
     *
     * @return Term[]
     */
    public function getTerms(): array
    {
        // If the object doesn't have an ID, no terms can be retrieved
        if ($this->getObjectId() === 0) {
            return [];
        }

        // If no terms exist, get them from the database and cache
        if (count($this->getTerms) === 0) {
            $this->getTerms = $this->termClass::get(
                ['object_ids' => $this->getObjectId()]
            );
        }

        return $this->getTerms;
    }

    // -------------------------------------------------------------------------

    /**
     * Add the terms to the object.
     *
     * Accepts an array of term IDs, term slugs, or `Term` instances.
     *
     * @param array $terms
     * @return array
     */
    public function addTerms(array $terms): array
    {
        $results = [];

        foreach ($terms as $term) {
            $results[] = $this->addTerm($term);
        }

        return $results;
    }

    /**
     * Add the term to the object.
     *
     * Accepts a term ID, term slug, or `Term` instance.
     *
     * @param int|string|Term $term
     * @return Result
     */
    public function addTerm(int|string|Term $term): Result
    {
        if (($result = $this->normalizeTerm($term))->hasFailed()) {
            return $result;
        }

        $this->addTerms[] = $result->getData();

        return $result;
    }

    // -------------------------------------------------------------------------

    /**
     * Remove the terms from the object.
     *
     * Accepts an array of term IDs, term slugs, or `Term` instances.
     *
     * @param array $terms
     * @return array
     */
    public function removeTerms(array $terms): array
    {
        $results = [];

        foreach ($terms as $term) {
            $results[] = $this->removeTerm($term);
        }

        return $results;
    }

    /**
     * Remove the term from the object.
     *
     * Accepts a term ID, term slug, or `Term` instance.
     *
     * @param int|string|Term $term
     * @return Result
     */
    public function removeTerm(int|string|Term $term): Result
    {
        if (($result = $this->normalizeTerm($term))->hasFailed()) {
            return $result;
        }

        $this->removeTerms[] = $result->getData();

        return $result;
    }

    // -------------------------------------------------------------------------

    /**
     * Set the terms on the object.
     *
     * Accepts an array of term IDs, term slugs, or `Term` instances.
     *
     * @param array $terms
     * @return array
     */
    public function setTerms(array $terms): array
    {
        $results = [];

        foreach ($terms as $term) {
            $results[] = $this->setTerm($term);
        }

        return $results;
    }

    /**
     * Set the term on the object.
     *
     * Accepts a term ID, term slug, or `Term` instance.
     *
     * @param int|string|Term $term
     * @return Result
     */
    public function setTerm(int|string|Term $term): Result
    {
        if (($result = $this->normalizeTerm($term))->hasFailed()) {
            return $result;
        }

        $this->setTerms[] = $result->getData();

        return $result;
    }

    // -------------------------------------------------------------------------

    /**
     * Persist the terms in the database.
     *
     * @return Result[]
     */
    public function persistTerms(): array
    {
        // If the object ID is zero, abort because without an object ID,
        // terms cannot be associated with an object
        if ($this->getObjectId() === 0) {
            return [Result::error(
                code: 'object_id_missing',
                message: __(
                    'Object ID is required to persist terms.',
                    'charm'
                )
            )];
        }

        $results = [];

        // Persist any new terms that might have been added or set
        $terms = array_merge($this->addTerms, $this->setTerms);
        foreach ($terms as $term) {
            if ($term->exists()) {
                continue;
            }
            $results[] = $term->create();
        }

        // Set terms on object and exit, because set will remove all terms
        // and would overwrite any terms that might have been added
        if (count($this->setTerms) > 0) {
            $results[] = static::setObjectTerms(
                objectId: $this->getObjectId(),
                terms: $this->extractTermIds($this->setTerms),
                taxonomy: $this->termClass::taxonomy()
            );
            $this->clearTerms();
            return $results;
        }

        // Remove terms from object
        if (count($this->removeTerms) > 0) {
            $results[] = static::removeObjectTerms(
                objectId: $this->getObjectId(),
                terms: $this->extractTermIds($this->removeTerms),
                taxonomy: $this->termClass::taxonomy()
            );
        }

        // Add terms to object
        if (count($this->addTerms) > 0) {
            $results[] = static::addObjectTerms(
                objectId: $this->getObjectId(),
                terms: $this->extractTermIds($this->addTerms),
                taxonomy: $this->termClass::taxonomy()
            );
        }

        $this->clearTerms();

        return $results;
    }

    /**
     * Clear all terms from instance.
     *
     * @return void
     */
    protected function clearTerms(): void
    {
        $this->getTerms = [];
        $this->addTerms = [];
        $this->removeTerms = [];
        $this->setTerms = [];
    }

    // *************************************************************************

    /**
     * Get the object ID.
     *
     * @return int Post ID, User ID, etc.
     */
    public function getObjectId(): int
    {
        return $this->objectId ?? 0;
    }

    /**
     * Set the object ID.
     *
     * @param int $objectId Post ID, User ID, etc.
     * @return $this
     */
    public function setObjectId(int $objectId): static
    {
        $this->objectId = $objectId;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the term class.
     *
     * @return class-string<Term> Category::class
     */
    public function getTermClass(): string
    {
        return $this->termClass ?? '';
    }

    /**
     * Set the term class.
     *
     * @param class-string<Term> $termClass Category::class
     * @return $this
     */
    public function setTermClass(string $termClass): static
    {
        $this->termClass = $termClass;

        return $this;
    }

    // *************************************************************************

    /**
     * Normalize the term.
     *
     * Takes a term ID or slug, provided it exists, and then converts it to a
     * `Term` instance.
     *
     * @param int|string|Term $term
     * @return Result
     */
    protected function normalizeTerm(int|string|Term $term): Result
    {
        // If term is already Term, return success with Term instance
        if ($term instanceof Term) {
            return Result::success()->withData($term);
        }

        // If the $termClass is not a subclass of the Term::class,
        // abort with an exception to ensure compliance
        if (!is_subclass_of($this->termClass, Term::class)) {
            return Result::error(
                code: 'term_class_invalid',
                message: __(
                    'Term class must be subclass of ' . Term::class . '.',
                    'charm'
                )
            )->withData($term);
        }

        /** @var Term $termClass */
        $termInstance = $this->termClass::init($term);

        // If term ID or slug doesn't exist, return error
        if ($termInstance === null) {
            return Result::error(
                code: 'term_not_found',
                message: __('Specified term does not exist', 'charm')
            )->withData($term);
        }

        // Otherwise, return success with Term instance
        return Result::success()->withData($termInstance);
    }

    /**
     * Extract term IDs from `Term` instances.
     *
     * @param Term[] $terms
     * @return array
     */
    protected function extractTermIds(array $terms): array
    {
        return array_map(
            fn($term) => $term->getId(),
            $terms
        );
    }
}