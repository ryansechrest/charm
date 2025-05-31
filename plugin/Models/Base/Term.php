<?php

namespace Charm\Models\Base;

use Charm\Contracts\IsPersistable;
use Charm\Contracts\Core\HasCoreTerm;
use Charm\Models\Metas\TermMeta;
use Charm\Models\Core;
use Charm\Support\Result;
use Charm\Traits\WithDeferredCalls;
use Charm\Traits\WithMeta;
use Charm\Traits\WithPersistenceState;
use WP_Term;
use WP_Term_Query;

/**
 * Represents a base term in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
abstract class Term implements HasCoreTerm, IsPersistable
{
    use WithDeferredCalls;
    use WithMeta;
    use WithPersistenceState;

    // -------------------------------------------------------------------------

    /**
     * Core term.
     *
     * @var ?Core\Term
     */
    protected ?Core\Term $coreTerm = null;

    // *************************************************************************

    /**
     * Ensures that the taxonomy gets defined.
     *
     * @return string `category`, `post_tag`, etc.
     */
    abstract public static function taxonomy(): string;

    /**
     * Set the class to be used when instantiating a term meta.
     *
     * @return string
     */
    protected static function metaClass(): string
    {
        return TermMeta::class;
    }

    // *************************************************************************

    /**
     * Term constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $data['taxonomy'] = static::taxonomy();
        $this->coreTerm = new Core\Term($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Get the core term instance.
     *
     * @return ?Core\Term
     */
    public function coreTerm(): ?Core\Term
    {
        return $this->coreTerm;
    }

    // *************************************************************************

    /**
     * Initialize the term.
     *
     * $key `int`     -> Term Taxonomy ID
     *      `string`  -> Term Slug
     *      `WP_Term` -> `WP_Term` instance
     *
     * @param int|string|WP_Term $key
     * @return ?static
     */
    public static function init(int|string|WP_Term $key): ?static
    {
        $coreTerm = match (true) {
            is_numeric($key) => Core\Term::fromTermTaxonomyId((int) $key),
            is_string($key) => Core\Term::fromSlug($key, static::taxonomy()),
            $key instanceof WP_Term => Core\Term::fromWpTerm($key),
            default => null,
        };

        if ($coreTerm === null) {
            return null;
        }

        if ($coreTerm->getTaxonomy() !== static::taxonomy()) {
            return null;
        }

        $term = new static();
        $term->coreTerm = $coreTerm;

        return $term;
    }

    /**
     * Initialize the term and preload all of its metas.
     *
     * @param int|string|WP_Term $key
     * @return ?static
     */
    public static function withMetas(int|string|WP_Term $key): ?static
    {
        return static::init($key)?->preloadMetas();
    }

    // -------------------------------------------------------------------------

    /**
     * Get the terms.
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
        $coreTerms = Core\Term::get($args);
        $terms = [];

        foreach ($coreTerms as $coreTerm) {
            $term = new static();
            $term->coreTerm = $coreTerm;
            $terms[] = $term;
        }

        return $terms;
    }

    /**
     * Query the terms with `WP_Term_Query`.
     *
     * See possible arguments:
     * https://developer.wordpress.org/reference/classes/wp_term_query/
     *
     * @param array $args
     * @return WP_Term_Query
     */
    public static function query(array $args): WP_Term_Query
    {
        return Core\Term::query($args);
    }

    // *************************************************************************

    /**
     * Save the term in the database,
     *
     * @return Result
     */
    public function save(): Result
    {
        return $this->coreTerm()->save();
    }

    /**
     * Create the term in the database.
     *
     * @return Result
     */
    public function create(): Result
    {
        return $this->coreTerm()->create();
    }

    /**
     * Update the term in the database.
     *
     * @return Result
     */
    public function update(): Result
    {
        return $this->coreTerm()->update();
    }

    /**
     * Delete the term from the database.
     *
     * @return Result
     */
    public function delete(): Result
    {
        return $this->coreTerm()->delete();
    }

    // *************************************************************************

    /**
     * Get the term ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->coreTerm()->getTermId();
    }

    // -------------------------------------------------------------------------

    /**
     * Get the term taxonomy ID.
     *
     * @return int
     */
    public function getTaxonomyId(): int
    {
        return $this->coreTerm()->getTermTaxonomyId();
    }

    // -------------------------------------------------------------------------

    /**
     * Get the term's name.
     *
     * @return string
     */
    public function getName(): string
    {
        /** @var HasCoreTerm $this */
        return $this->coreTerm()->getName();
    }

    /**
     * Set the term's name.
     *
     * @param string $name
     * @return static
     */
    public function setName(string $name): static
    {
        /** @var HasCoreTerm $this */
        $this->coreTerm()->setName($name);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the term's slug.
     *
     * @return string
     */
    public function getSlug(): string
    {
        /** @var HasCoreTerm $this */
        return $this->coreTerm()->getSlug();
    }

    /**
     * Set the term's slug.
     *
     * @param string $slug
     * @return static
     */
    public function setSlug(string $slug): static
    {
        /** @var HasCoreTerm $this */
        $this->coreTerm()->setSlug($slug);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether the term exists in the database.
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->coreTerm()->exists();
    }
}