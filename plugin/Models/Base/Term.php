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
     * Proxy term
     *
     * @var ?Proxy\Term
     */
    protected ?Proxy\Term $proxyTerm = null;

    // *************************************************************************

    /**
     * Force taxonomy definition
     *
     * e.g. `category`, `post_tag`, etc.
     *
     * @return string
     */
    abstract protected static function taxonomy(): string;

    /**
     * Define default meta class
     *
     * @return string
     */
    protected static function metaClass(): string
    {
        return TermMeta::class;
    }

    // *************************************************************************

    /**
     * Term constructor
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
     * Get proxy term instance
     *
     * @return ?Proxy\Term
     */
    public function proxyTerm(): ?Proxy\Term
    {
        return $this->proxyTerm;
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
     * Initialize term and preload metas
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
        return Proxy\Term::query($args);
    }

    // *************************************************************************

    /**
     * Save term
     *
     * @return Result
     */
    public function save(): Result
    {
        return $this->proxyTerm()->save();
    }

    /**
     * Create new term
     *
     * @return Result
     */
    public function create(): Result
    {
        return $this->proxyTerm()->create();
    }

    /**
     * Update existing term
     *
     * @return Result
     */
    public function update(): Result
    {
        return $this->proxyTerm()->update();
    }

    /**
     * Delete term
     *
     * @return Result
     */
    public function delete(): Result
    {
        return $this->proxyTerm()->delete();
    }

    // -------------------------------------------------------------------------

    /**
     * Add term to model (appending to existing terms)
     *
     * @param $modelId
     * @return Result
     */
    public function addModel($modelId): Result
    {
        return $this->proxyTerm()::addObjectTerms(
            objectId: $modelId,
            terms: $this->getId(),
            taxonomy: static::taxonomy()
        );
    }

    /**
     * Remove term from model
     *
     * @param $modelId
     * @return Result
     */
    public function removeModel($modelId): Result
    {
        return $this->proxyTerm()::removeObjectTerms(
            objectId: $modelId,
            terms: $this->getId(),
            taxonomy: static::taxonomy()
        );
    }

    /**
     * Set term on model (replacing existing terms)
     *
     * @param $modelId
     * @return Result
     */
    public function setModel($modelId): Result
    {
        return $this->proxyTerm()::setObjectTerms(
            objectId: $modelId,
            terms: $this->getId(),
            taxonomy: static::taxonomy()
        );
    }

    // *************************************************************************

    /**
     * Get term ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->proxyTerm()->getTermId();
    }

    // -------------------------------------------------------------------------

    /**
     * Get term taxonomy ID
     *
     * @return int
     */
    public function getTaxonomyId(): int
    {
        return $this->proxyTerm()->getTermTaxonomyId();
    }

    // -------------------------------------------------------------------------

    /**
     * Get term name
     *
     * @return string
     */
    public function getName(): string
    {
        /** @var HasProxyTerm $this */
        return $this->proxyTerm()->getName();
    }

    /**
     * Set term name
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
     * Get term slug
     *
     * @return string
     */
    public function getSlug(): string
    {
        /** @var HasProxyTerm $this */
        return $this->proxyTerm()->getSlug();
    }

    /**
     * Set term slug
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
     * Whether term exists in database
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->proxyTerm()->exists();
    }
}