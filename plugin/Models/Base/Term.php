<?php

namespace Charm\Models\Base;

use Charm\Contracts\HasWpTerm;
use Charm\Contracts\IsPersistable;
use Charm\Models\WordPress;
use Charm\Support\Cast;
use Charm\Support\Result;
use Charm\Traits\WithPersistenceState;
use WP_Term;
use WP_Term_Query;

/**
 * Represents a base meta in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
abstract class Term implements HasWpTerm, IsPersistable
{
    use WithPersistenceState;

    // -------------------------------------------------------------------------

    /**
     * WordPress term
     *
     * @var ?WordPress\Term
     */
    protected ?WordPress\Term $wpTerm = null;

    // *************************************************************************

    /**
     * Force taxonomy definition
     *
     * e.g. `category`, `post_tag`, etc.
     *
     * @return string
     */
    abstract protected static function taxonomy(): string;

    // *************************************************************************

    /**
     * Term constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $data['taxonomy'] = static::taxonomy();
        $this->wpTerm = new WordPress\Term($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Get WordPress term instance
     *
     * @return ?WordPress\Term
     */
    public function wp(): ?WordPress\Term
    {
        return $this->wpTerm;
    }

    // *************************************************************************

    /**
     * Initialize term
     *
     * int     -> Term Taxonomy ID
     * string  -> Term Slug
     * WP_Term -> WP_Term instance
     *
     * @param int|string|WP_Term $key
     * @return ?static
     */
    public static function init(int|string|WP_Term $key): ?static
    {
        $wpTerm = match (true) {
            is_numeric($key) => WordPress\Term::fromTermTaxonomyId((int) $key),
            is_string($key) => WordPress\Term::fromSlug($key, static::taxonomy()),
            $key instanceof WP_Term => WordPress\Term::fromWpTerm($key),
            default => null,
        };

        if ($wpTerm === null) {
            return null;
        }

        if ($wpTerm->getTaxonomy() !== static::taxonomy()) {
            return null;
        }

        $term = new static();
        $term->wpTerm = $wpTerm;

        return $term;
    }

    // -------------------------------------------------------------------------

    /**
     * Get terms
     *
     * See possible arguments:
     * https://developer.wordpress.org/reference/classes/wp_term_query/
     *
     * @param array $args
     * @return static[]
     */
    public static function get(array $args = ['hide_empty' => false]): array
    {
        $args['taxonomy'] = static::taxonomy();
        $wpTerms = WordPress\Term::get($args);
        $terms = [];

        foreach ($wpTerms as $wpTerm) {
            $term = new static();
            $term->wpTerm = $wpTerm;
            $terms[] = $term;
        }

        return $terms;
    }

    /**
     * Query terms with WP_Term_Query
     *
     * See possible arguments:
     * https://developer.wordpress.org/reference/classes/wp_term_query/
     *
     * @param array $args
     * @return WP_Term_Query
     */
    public static function query(array $args): WP_Term_Query
    {
        return WordPress\Term::query($args);
    }

    // *************************************************************************

    /**
     * Save term
     *
     * @return Result
     */
    public function save(): Result
    {
        return $this->wp()->save();
    }

    /**
     * Create new term
     *
     * @return Result
     */
    public function create(): Result
    {
        return $this->wp()->create();
    }

    /**
     * Update existing term
     *
     * @return Result
     */
    public function update(): Result
    {
        return $this->wp()->update();
    }

    /**
     * Delete term
     *
     * @return Result
     */
    public function delete(): Result
    {
        return $this->wp()->delete();
    }

    // *************************************************************************

    /**
     * Get term taxonomy ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->wp()->getTermTaxonomyId();
    }

    // -------------------------------------------------------------------------

    /**
     * Whether term exists in database
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->wp()->exists();
    }
}