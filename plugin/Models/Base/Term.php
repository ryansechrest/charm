<?php

namespace Charm\Models\Base;

use Charm\Contracts\IsPersistable;
use Charm\Contracts\Proxy\HasProxyTerm;
use Charm\Models\Metas\TermMeta;
use Charm\Models\Proxy;
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
abstract class Term implements HasProxyTerm, IsPersistable
{
    use WithDeferredCalls;
    use WithMeta;
    use WithPersistenceState;

    // -------------------------------------------------------------------------

    /**
     * Proxy term.
     *
     * @var ?Proxy\Term
     */
    protected ?Proxy\Term $proxyTerm = null;

    // *************************************************************************

    /**
     * Ensures that the taxonomy gets defined.
     *
     * @return string `category`, `post_tag`, etc.
     */
    abstract protected static function taxonomy(): string;

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
        $this->proxyTerm = new Proxy\Term($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Get the proxy term instance.
     *
     * @return ?Proxy\Term
     */
    public function proxyTerm(): ?Proxy\Term
    {
        return $this->proxyTerm;
    }

    // *************************************************************************

    /**
     * Initialize the term.
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
        $proxyTerm = match (true) {
            is_numeric($key) => Proxy\Term::fromTermTaxonomyId((int) $key),
            is_string($key) => Proxy\Term::fromSlug($key, static::taxonomy()),
            $key instanceof WP_Term => Proxy\Term::fromWpTerm($key),
            default => null,
        };

        if ($proxyTerm === null) {
            return null;
        }

        if ($proxyTerm->getTaxonomy() !== static::taxonomy()) {
            return null;
        }

        $term = new static();
        $term->proxyTerm = $proxyTerm;

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
        $proxyTerms = Proxy\Term::get($args);
        $terms = [];

        foreach ($proxyTerms as $proxyTerm) {
            $term = new static();
            $term->proxyTerm = $proxyTerm;
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
        return Proxy\Term::query($args);
    }

    // *************************************************************************

    /**
     * Save the term in the database,
     *
     * @return Result
     */
    public function save(): Result
    {
        return $this->proxyTerm()->save();
    }

    /**
     * Create the term in the database.
     *
     * @return Result
     */
    public function create(): Result
    {
        return $this->proxyTerm()->create();
    }

    /**
     * Update the term in the database.
     *
     * @return Result
     */
    public function update(): Result
    {
        return $this->proxyTerm()->update();
    }

    /**
     * Delete the term from the database.
     *
     * @return Result
     */
    public function delete(): Result
    {
        return $this->proxyTerm()->delete();
    }

    // *************************************************************************

    /**
     * Get the term ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->proxyTerm()->getTermId();
    }

    // -------------------------------------------------------------------------

    /**
     * Get the term taxonomy ID.
     *
     * @return int
     */
    public function getTaxonomyId(): int
    {
        return $this->proxyTerm()->getTermTaxonomyId();
    }

    // -------------------------------------------------------------------------

    /**
     * Get the term's name.
     *
     * @return string
     */
    public function getName(): string
    {
        /** @var HasProxyTerm $this */
        return $this->proxyTerm()->getName();
    }

    /**
     * Set the term's name.
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

    // -------------------------------------------------------------------------

    /**
     * Get the term's slug.
     *
     * @return string
     */
    public function getSlug(): string
    {
        /** @var HasProxyTerm $this */
        return $this->proxyTerm()->getSlug();
    }

    /**
     * Set the term's slug.
     *
     * @param string $slug
     * @return static
     */
    public function setSlug(string $slug): static
    {
        /** @var HasProxyTerm $this */
        $this->proxyTerm()->setSlug($slug);

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
        return $this->proxyTerm()->exists();
    }
}