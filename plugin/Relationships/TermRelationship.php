<?php

namespace Charm\Relationships;

use Charm\Models\Base\Term;
use Charm\Support\Result;
use WP_Error;

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
        $args = [
            'objectId' => $objectId,
            'terms' => $terms,
            'taxonomy' => $taxonomy,
        ];

        // `array`  -> Term Taxonomy IDs -> Success: Object terms added
        // `object` -> `WP_Error`        -> Fail: Object terms not added
        $result = wp_add_object_terms(
            object_id: $objectId, terms: $terms, taxonomy: $taxonomy
        );

        if ($result instanceof WP_Error) {
            return Result::error(
                'term_relationship_add_failed',
                'Terms could not be added to the object. `wp_add_object_terms()` return a `WP_Error` object.'
            )->setObjectId($objectId)->setFunctionReturn($result)->setFunctionArgs($args);
        }

        if (!is_array($result)) {
            return Result::error(
                'term_relationship_add_failed',
                'Terms could not be added to the object. Expected `wp_add_object_terms()` to return an array of term taxonomy IDs, but received an unexpected result.'
            )->setObjectId($objectId)->setFunctionReturn($result)->setFunctionArgs($args);
        }

        return Result::success(
            'term_relationship_add_success',
            'Terms successfully added to the object.'
        )->setObjectId($objectId)->setFunctionReturn($result)->setFunctionArgs($args);
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
        $args = [
            'objectId' => $objectId,
            'terms' => $terms,
            'taxonomy' => $taxonomy,
        ];

        // `bool`   -> `true`     -> Success: Object terms removed
        // `bool`   -> `false`     > Fail: Object terms not removed
        // `object` -> `WP_Error` -> Fail: Object terms not removed
        $result = wp_remove_object_terms(
            object_id: $objectId, terms: $terms, taxonomy: $taxonomy
        );

        if ($result instanceof WP_Error) {
            return Result::error(
                'term_relationship_remove_failed',
                'Terms could not be removed from the object. `wp_remove_object_terms()` return a `WP_Error` object.'
            )->setObjectId($objectId)->setFunctionReturn($result)->setFunctionArgs($args);
        }

        if ($result === false) {
            return Result::error(
                'term_relationship_remove_failed',
                'Terms could not be removed from the object. `wp_remove_object_terms()` return `false`.'
            )->setObjectId($objectId)->setFunctionReturn(false)->setFunctionArgs($args);
        }

        if ($result !== true) {
            return Result::error(
                'term_relationship_remove_failed',
                'Terms could not be removed from the object. Expected `wp_remove_object_terms()` to return `true`, but received an unexpected result.'
            )->setObjectId($objectId)->setFunctionReturn($result)->setFunctionArgs($args);
        }

        return Result::success(
            'term_relationship_remove_success',
            'Terms successfully removed from the object.'
        )->setObjectId($objectId)->setFunctionReturn(true)->setFunctionArgs($args);
    }

    /**
     * Set terms on an object (replaces terms).
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
        $args = [
            'objectId' => $objectId,
            'terms' => $terms,
            'taxonomy' => $taxonomy,
        ];

        // `array`  -> Term Taxonomy IDs -> Success: Object terms set
        // `object` -> `WP_Error`        -> Fail: Object terms not set
        $result = wp_set_object_terms(
            object_id: $objectId, terms: $terms, taxonomy: $taxonomy
        );

        if ($result instanceof WP_Error) {
            return Result::error(
                'term_relationship_set_failed',
                'Terms could not be set on the object. `wp_set_object_terms()` return a `WP_Error` object.'
            )->setObjectId($objectId)->setFunctionReturn($result)->setFunctionArgs($args);
        }

        if (!is_array($result)) {
            return Result::error(
                'term_relationship_set_failed',
                'Terms could not be set on the object. Expected `wp_set_object_terms()` to return an array of term taxonomy IDs, but received an unexpected result.'
            )->setFunctionReturn($result)->setFunctionArgs($args);
        }

        return Result::success(
            'term_relationship_set_success',
            'Terms successfully set on the object.'
        )->setObjectId($objectId)->setFunctionReturn($result)->setFunctionArgs($args);
    }

    /**
     * Check whether the object has specified terms.
     *
     * $terms `int`    -> Term ID
     *        `string` -> Term Name/Slug
     *        `array`  -> Term IDs/Names/Slugs
     *
     * @param int $objectId 1
     * @param string $taxonomy category
     * @param array $terms
     * @return Result
     */
    public static function hasObjectTerms(
        int $objectId, string $taxonomy, array $terms = []
    ): Result
    {
        $args = [
            'objectId' => $objectId,
            'taxonomy' => $taxonomy,
            'terms' => $terms,
        ];

        // @todo WordPress returns `true` if it finds at least one of the
        //   specified terms on the object, but it would feel more intuitive to
        //   only return `true` if all of the terms are found.

        // `bool`   -> `true`     -> Success: Object has terms
        // `bool`   -> `false`    -> Fail: Object does not have terms
        // `object` -> `WP_Error` -> Fail: Object could not be checked
        $result = is_object_in_term(
            object_id: $objectId, taxonomy: $taxonomy, terms: $terms
        );

        if ($result instanceof WP_Error) {
            return Result::error(
                'term_relationship_has_failed',
                'Object could not be checked for the specified terms. `is_object_in_term()` return a `WP_Error` object.'
            )->setObjectId($objectId)->setFunctionReturn($result)->setFunctionArgs($args);
        }

        if ($result === false) {
            return Result::error(
                'term_relationship_has_failed',
                'Object does not have any of the specified terms. `is_object_in_term()` return `false`.'
            )->setObjectId($objectId)->setFunctionReturn(false)->setFunctionArgs($args);
        }

        if ($result !== true) {
            return Result::error(
                'term_relationship_has_failed',
                'Object does not have any of the specified terms. Expected `is_object_in_term()` to return `true`, but received an unexpected result.'
            )->setObjectId($objectId)->setFunctionReturn($result)->setFunctionArgs($args);
        }

        return Result::success(
            'term_relationship_has_success',
            'Object has one or more of the specified terms.'
        )->setObjectId($objectId)->setFunctionReturn(true)->setFunctionArgs($args);
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
     * @param array $terms Array of term IDs, slugs, or instances
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
     * @param int|string|Term $term Term ID, slug, or instance
     * @return Result
     */
    public function addTerm(int|string|Term $term): Result
    {
        $args = ['term' => $term];

        if (!$normalizedTerm = $this->normalizeTerm($term)) {
            $this->addTerms[] = new $this->termClass(['name' => $term]);
            return Result::info(
                'term_relationship_add_info',
                'Term could not be initialized from its ID or slug, so it was created instead.'
            )->setFunctionArgs($args);
        }

        $this->addTerms[] = $normalizedTerm;

        return Result::success(
            'term_relationship_add_success',
            'Term successfully saved to be added to the object.'
        )->setFunctionArgs($args);
    }

    // -------------------------------------------------------------------------

    /**
     * Remove the terms from the object.
     *
     * @param array $terms Array of term IDs, slugs, or instances
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
     * @param int|string|Term $term Term ID, slug, or instance
     * @return Result
     */
    public function removeTerm(int|string|Term $term): Result
    {
        $args = ['term' => $term];

        if (!$normalizedTerm = $this->normalizeTerm($term)) {
            return Result::error(
                'term_relationship_remove_failed',
                'Term could not be initialized from its ID or slug.'
            )->setFunctionArgs($args);
        }

        $this->removeTerms[] = $normalizedTerm;

        return Result::success(
            'term_relationship_remove_success',
            'Term successfully saved to be removed from the object.'
        )->setFunctionArgs($args);
    }

    // -------------------------------------------------------------------------

    /**
     * Set the terms on the object.
     *
     * @param array $terms Array of term IDs, slugs, or instances
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
     * @param int|string|Term $term Term ID, slug, or instance
     * @return Result
     */
    public function setTerm(int|string|Term $term): Result
    {
        $args = ['term' => $term];

        if ($normalizedTerm = $this->normalizeTerm($term)) {
            $this->setTerms[] = new $this->termClass(['name' => $term]);
            return Result::info(
                'term_relationship_set_info',
                'Term could not be initialized from its ID or slug, so it was created instead.'
            )->setFunctionArgs($args);
        }

        $this->setTerms[] = $normalizedTerm;

        return Result::success(
            'term_relationship_set_success',
            'Term successfully saved to be set on the object.'
        )->setFunctionArgs($args);
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether the object has a single specific term.
     *
     * @param int|string|Term $term Term ID, slug, or instance
     * @return bool
     */
    public function hasTerm(int|string|Term $term): bool
    {
        if (!$normalizedTerm = $this->normalizeTerm($term)) {
            return false;
        }

        $result = static::hasObjectTerms(
            objectId: $this->getObjectId(),
            taxonomy: $this->termClass::taxonomy(),
            terms: [$normalizedTerm->getId()]
        );

        return $result->hasSucceeded();
    }

    /**
     * Check whether the object has ALL the specified terms.
     *
     * @param array $terms Array of term IDs, slugs, or instances
     * @return bool
     */
    public function hasAllTerms(array $terms): bool
    {
        // If there were no terms provided, then the object can't have all
        if (count($terms) === 0) {
            return false;
        }

        $normalizedTerms = [];
        $failedNormalizations = [];

        foreach ($terms as $term) {
            if ($normalizedTerm = $this->normalizeTerm($term)) {
                $normalizedTerms[] = $normalizedTerm;
                continue;
            }
            $failedNormalizations[] = $term;
        }

        // If there are failed normalizations, then the object can't have all
        if (count($failedNormalizations) > 0) {
            return false;
        }

        $missingTermIds = array_diff(
            $this->extractTermIds($normalizedTerms),
            $this->extractTermIds($this->getTerms())
        );

        // If there are missing Term IDs, then the object doesn't have all
        if (count($missingTermIds) > 0) {
            return false;
        }

        return true;
    }

    /**
     * Check whether the object has ANY of the specified terms.
     *
     * @param array $terms Array of term IDs, slugs, or instances
     * @return bool
     */
    public function hasAnyTerms(array $terms): bool
    {
        // If there were no terms provided, then the object can't have any
        if (count($terms) === 0) {
            return false;
        }

        $normalizedTerms = [];

        foreach ($terms as $term) {
            if (!$normalizedTerm = $this->normalizeTerm($term)) {
                continue;
            }
            $normalizedTerms[] = $normalizedTerm;
        }


        // If there are no normalized terms, then the object can't have any
        if (count($normalizedTerms) === 0) {
            return false;
        }

        $result = static::hasObjectTerms(
            objectId: $this->getObjectId(),
            taxonomy: $this->termClass::taxonomy(),
            terms: $this->extractTermIds($normalizedTerms)
        );

        return $result->hasSucceeded();
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
                'term_relationship_persist_failed',
                'Terms were not persisted because the object ID is zero.'
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

        // If terms are set, persist them on the object and exit, because
        // setting terms takes precedence over terms that might have been
        // added or removed
        if (count($this->setTerms) > 0) {
            $results[] = static::setObjectTerms(
                objectId: $this->getObjectId(),
                terms: $this->extractTermIds($this->setTerms),
                taxonomy: $this->termClass::taxonomy()
            );
            $this->clearTerms();
            return $results;
        }

        // Remove terms from the object
        if (count($this->removeTerms) > 0) {
            $results[] = static::removeObjectTerms(
                objectId: $this->getObjectId(),
                terms: $this->extractTermIds($this->removeTerms),
                taxonomy: $this->termClass::taxonomy()
            );
        }

        // Add terms to the object
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
     * @return ?Term
     */
    protected function normalizeTerm(int|string|Term $term): ?Term
    {
        // If the term is already a `Term` instance, then return it
        if ($term instanceof Term) {
            return $term;
        }

        /** @var Term $termClass */
        return $this->termClass::init($term);
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