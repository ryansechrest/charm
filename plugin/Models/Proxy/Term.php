<?php

namespace Charm\Models\Proxy;

use Charm\Contracts\IsPersistable;
use Charm\Contracts\WordPress\HasWpTerm;
use Charm\Support\Result;
use WP_Error;
use WP_Term;
use WP_Term_Query;

/**
 * Represents a proxy term belonging to any taxonomy in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Term implements HasWpTerm, IsPersistable
{
    /**
     * Term ID.
     *
     * Table: `wp_terms`
     * Column: `term_id`
     *
     * @var ?int
     */
    protected ?int $termId = null;

    /**
     * Term name.
     *
     * Table: `wp_terms`
     * Column: `name`
     *
     * @var ?string
     */
    protected ?string $name = null;

    /**
     * Term slug.
     *
     * Table: `wp_terms`
     * Column: `slug`
     *
     * @var ?string
     */
    protected ?string $slug = null;

    // -------------------------------------------------------------------------

    /**
     * Term taxonomy ID.
     *
     * Table: `wp_term_taxonomy`
     * Column: `term_taxonomy_id`
     *
     * @var ?int
     */
    protected ?int $termTaxonomyId = null;

    /**
     * Term taxonomy slug.
     *
     * Table: `wp_term_taxonomy`
     * Column: `taxonomy`
     *
     * @var ?string
     */
    protected ?string $taxonomy = null;

    /**
     * Term taxonomy description.
     *
     * Table: `wp_term_taxonomy`
     * Column: `taxonomy`
     *
     * @var ?string
     */
    protected ?string $description = null;

    /**
     * Parent term taxonomy ID.
     *
     * Table: `wp_term_taxonomy`
     * Column: `parent`
     *
     * @var ?int
     */
    protected ?int $parent = null;

    /**
     * Number of objects using the term taxonomy.
     *
     * Table: `wp_term_taxonomy`
     * Column: `count`
     *
     * @var ?int
     */
    protected ?int $count = null;

    // -------------------------------------------------------------------------

    /**
     * `WP_Term` instance.
     *
     * @var ?WP_Term
     */
    protected ?WP_Term $wpTerm = null;

    // *************************************************************************

    /**
     * Term constructor.
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
        if (isset($data['termId'])) {
            $this->termId = (int) $data['termId'];
        }

        if (isset($data['name'])) {
            $this->name = $data['name'];
        }

        if (isset($data['slug'])) {
            $this->slug = $data['slug'];
        }

        if (isset($data['termTaxonomyId'])) {
            $this->termTaxonomyId = (int) $data['termTaxonomyId'];
        }

        if (isset($data['taxonomy'])) {
            $this->taxonomy = $data['taxonomy'];
        }

        if (isset($data['description'])) {
            $this->description = $data['description'];
        }

        if (isset($data['parent'])) {
            $this->parent = (int) $data['parent'];
        }

        if (isset($data['count'])) {
            $this->count = (int) $data['count'];
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Access the `WP_Term` instance.
     *
     * @return ?WP_Term
     */
    public function wpTerm(): ?WP_Term
    {
        return $this->wpTerm;
    }

    // *************************************************************************

    /**
     * Initialize the term from a term ID.
     *
     * From: `wp_terms` -> `term_id`
     *       `wp_term_taxonomy` -> `taxonomy`
     *
     * @param int $termId 1
     * @param string $taxonomy category
     * @return ?static
     */
    public static function fromTermId(int $termId, string $taxonomy): ?static
    {
        $term = new static();
        $term->loadFromTermId($termId, $taxonomy);

        return $term->termId ? $term : null;
    }

    /**
     * Initialize the term from a term name.
     *
     * From: `wp_terms` -> `name`
     *       `wp_term_taxonomy` -> `taxonomy`
     *
     * @param string $name Uncategorized
     * @param string $taxonomy category
     * @return ?static
     */
    public static function fromName(string $name, string $taxonomy): ?static
    {
        $term = new static();
        $term->loadFromName($name, $taxonomy);

        return $term->termId ? $term : null;
    }

    /**
     * Initialize the term from a term slug.
     *
     * From: `wp_terms` -> `slug`
     *       `wp_term_taxonomy` -> `taxonomy`
     *
     * @param string $slug uncategorized
     * @param string $taxonomy category
     * @return ?static
     */
    public static function fromSlug(string $slug, string $taxonomy): ?static
    {
        $term = new static();
        $term->loadFromSlug($slug, $taxonomy);

        return $term->termId ? $term : null;
    }

    /**
     * Initialize the term from a term taxonomy ID.
     *
     * From: `wp_term_taxonomy` -> `term_taxonomy_id`
     *
     * @param int $termTaxonomyId
     * @return ?static
     */
    public static function fromTermTaxonomyId(int $termTaxonomyId): ?static
    {
        $term = new static();
        $term->loadFromTermTaxonomyId($termTaxonomyId);

        return $term->termId ? $term : null;
    }

    /**
     * Initialize the term from a `WP_Term` instance.
     *
     * @param WP_Term $wpTerm
     * @return static
     */
    public static function fromWpTerm(WP_Term $wpTerm): static
    {
        $term = new static();
        $term->loadFromWpTerm($wpTerm);

        return $term;
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
    public static function get(array $args): array
    {
        $wpTermQuery = self::query(
            array_merge($args, ['fields' => 'all'])
        );

        if (count($wpTermQuery->get_terms()) === 0) {
            return [];
        }

        return array_map(
            fn(WP_Term $wpTerm) => static::fromWpTerm($wpTerm),
            $wpTermQuery->get_terms()
        );
    }

    /**
     * Query the terms with `WP_Term_Query`.
     *
     * See possible arguments:
     * https://developer.wordpress.org/reference/classes/wp_term_query/
     *
     * @param array $params
     * @return WP_Term_Query
     */
    public static function query(array $params): WP_Term_Query
    {
        return new WP_Term_Query(query: $params);
    }

    // -------------------------------------------------------------------------

    /**
     * Create a term in a taxonomy.
     *
     * @param string $name Uncategorized
     * @param string $taxonomy category
     * @param array $args ['slug' => '', 'parent' => 0, 'description' => '']
     * @return Result
     * @see wp_insert_term()
     */
    public static function createTerm(
        string $name, string $taxonomy, array $args
    ): Result
    {
        $result = wp_insert_term(
            term: $name, taxonomy: $taxonomy, args: $args
        );

        if ($result instanceof WP_Error) {
            return Result::wpError(wpError: $result);
        }

        return Result::success()->withData([
            'termId' => (int) $result['term_id'] ?? 0,
            'termTaxonomyId' => (int) $result['term_taxonomy_id'] ?? 0,
        ]);
    }

    /**
     * Update a term in a taxonomy.
     *
     * @param int $termId 1
     * @param string $taxonomy category
     * @param array $args ['slug' => '', 'parent' => 0, 'description' => '']
     * @return Result
     * @see wp_update_term()
     */
    public static function updateTerm(
        int $termId, string $taxonomy, array $args
    ): Result
    {
        $result = wp_update_term(
            term_id: $termId, taxonomy: $taxonomy, args: $args
        );

        if ($result instanceof WP_Error) {
            return Result::wpError(wpError: $result);
        }

        return Result::success()->withData([
            'termId' => (int) $result['term_id'] ?? 0,
            'termTaxonomyId' => (int) $result['term_taxonomy_id'] ?? 0,
        ]);
    }

    /**
     * Delete a term from a taxonomy.
     *
     * @param int $termId 1
     * @param string $taxonomy category
     * @return Result
     * @see wp_delete_term()
     */
    public static function deleteTerm(int $termId, string $taxonomy): Result
    {
        $result = wp_delete_term(term: $termId, taxonomy: $taxonomy);

        if ($result instanceof WP_Error) {
            return Result::wpError(wpError: $result);
        }

        if ($result !== true) {
            return Result::error(
                code: 'wp_delete_term_failed',
                message: __('wp_delete_term() did not return true.', 'charm')
            );
        }

        return Result::success();
    }

    // *************************************************************************

    /**
     * Save the term in the taxonomy.
     *
     * @return Result
     */
    public function save(): Result
    {
        return $this->termTaxonomyId === null
            ? $this->create() : $this->update();
    }

    /**
     * Create the term in the taxonomy.
     *
     * @return Result
     */
    public function create(): Result
    {
        if ($this->termId !== null) {
            return Result::error(
                code: 'term_id_exists',
                message: __('Term already exists.', 'charm')
            )->withData($this);
        }

        if ($this->taxonomy === null) {
            return Result::error(
                code: 'taxonomy_missing',
                message: __('Cannot create term without taxonomy.', 'charm')
            )->withData($this);
        }

        $result = static::createTerm(
            name: $this->name,
            taxonomy: $this->taxonomy,
            args: [
                'slug' => $this->slug,
                'description' => $this->description,
                'parent' => $this->parent,
            ]
        );

        if ($result->hasFailed()) {
            return $result;
        }

        $resultData = $result->getData();
        $this->termId = $resultData['termId'] ?? 0;
        $this->termTaxonomyId = $resultData['termTaxonomyId'] ?? 0;
        $this->reload();

        return $result;
    }

    /**
     * Update the term in the taxonomy.
     *
     * @return Result
     */
    public function update(): Result
    {
        if ($this->termId === null) {
            return Result::error(
                code: 'term_id_missing',
                message: __('Cannot update term with blank term ID.', 'charm')
            )->withData($this);
        }

        if ($this->taxonomy === null) {
            return Result::error(
                code: 'taxonomy_missing',
                message: __('Cannot update term without taxonomy.', 'charm')
            )->withData($this);
        }

        $result = static::updateTerm(
            termId: $this->termId,
            taxonomy: $this->taxonomy,
            args: [
                'slug' => $this->slug,
                'description' => $this->description,
                'parent' => $this->parent,
            ]
        );

        if ($result->hasFailed()) {
            return $result;
        }

        $resultData = $result->getData();
        $this->termId = $resultData['termId'] ?? 0;
        $this->termTaxonomyId = $resultData['termTaxonomyId'] ?? 0;
        $this->reload();

        return $result;
    }

    /**
     * Delete the term from the taxonomy.
     *
     * @return Result
     */
    public function delete(): Result
    {
        if ($this->termId === null) {
            return Result::error(
                code: 'term_id_missing',
                message: __('Cannot delete term with blank term ID.', 'charm')
            )->withData($this);
        }

        if ($this->taxonomy === null) {
            return Result::error(
                code: 'taxonomy_missing',
                message: __('Cannot delete term without taxonomy.', 'charm')
            )->withData($this);
        }

        $result = static::deleteTerm(
            termId: $this->termId, taxonomy: $this->taxonomy
        );

        if ($result->hasFailed()) {
            return $result;
        }

        $this->termId = null;
        $this->termTaxonomyId = null;

        return $result;
    }

    // *************************************************************************

    /**
     * Get the term ID.
     *
     * @return int
     */
    public function getTermId(): int
    {
        return $this->termId ?? 0;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the term name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name ?? '';
    }

    /**
     * Set the term name.
     *
     * @param string $name
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the term slug.
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug ?? '';
    }

    /**
     * Set the term slug.
     *
     * @param string $slug
     * @return static
     */
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the term taxonomy ID.
     *
     * @return int
     */
    public function getTermTaxonomyId(): int
    {
        return $this->termTaxonomyId ?? 0;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the taxonomy.
     *
     * @return string
     */
    public function getTaxonomy(): string
    {
        return $this->taxonomy ?? '';
    }

    // -------------------------------------------------------------------------

    /**
     * Get the term description in the taxonomy.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description ?? '';
    }

    /**
     * Set the term description in the taxonomy.
     *
     * @param string $description
     * @return static
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the parent term in the taxonomy.
     *
     * @return int
     */
    public function getParent(): int
    {
        return $this->parent ?? 0;
    }

    /**
     * Set the parent term in the taxonomy.
     *
     * @param int $parent
     * @return static
     */
    public function setParent(int $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the number of objects using that term within the taxonomy.
     *
     * @return int
     */
    public function getCount(): int
    {
        return $this->count ?? 0;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether the term exists in the database.
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->getTermTaxonomyId() > 0;
    }

    // *************************************************************************

    /**
     * Load the instance from a term ID.
     *
     * @param int $termId 1
     * @param string $taxonomy category
     * @return void
     */
    protected function loadFromTermId(int $termId, string $taxonomy): void
    {
        $wpTerm = get_term_by(
            field: 'term_id', value: $termId, taxonomy: $taxonomy
        );

        if (!$wpTerm instanceof WP_Term) {
            return;
        }

        $this->loadFromWpTerm($wpTerm);
    }

    /**
     * Load the instance from a term name.
     *
     * @param string $name Uncategorized
     * @param string $taxonomy category
     * @return void
     */
    protected function loadFromName(string $name, string $taxonomy): void
    {
        $wpTerm = get_term_by(
            field: 'name', value: $name, taxonomy: $taxonomy
        );

        if (!$wpTerm instanceof WP_Term) {
            return;
        }

        $this->loadFromWpTerm($wpTerm);
    }

    /**
     * Load the instance from a term slug.
     *
     * @param string $slug uncategorized
     * @param string $taxonomy category
     * @return void
     */
    protected function loadFromSlug(string $slug, string $taxonomy): void
    {
        $wpTerm = get_term_by(
            field: 'slug', value: $slug, taxonomy: $taxonomy
        );

        if (!$wpTerm instanceof WP_Term) {
            return;
        }

        $this->loadFromWpTerm($wpTerm);
    }

    /**
     * Load the instance from term taxonomy ID.
     *
     * @param int $termTaxonomyId
     * @return void
     */
    protected function loadFromTermTaxonomyId(int $termTaxonomyId): void
    {
        $wpTerm = get_term_by(
            field: 'term_taxonomy_id', value: $termTaxonomyId
        );

        if (!$wpTerm instanceof WP_Term) {
            return;
        }

        $this->loadFromWpTerm($wpTerm);
    }

    /**
     * Load the instance from a `WP_Term` instance.
     *
     * @param WP_Term $wpTerm
     */
    protected function loadFromWpTerm(WP_Term $wpTerm): void
    {
        $this->wpTerm = $wpTerm;

        $this->termId = (int) $wpTerm->term_id;
        $this->name = $wpTerm->name;
        $this->slug = $wpTerm->slug;
        $this->termTaxonomyId = (int) $wpTerm->term_taxonomy_id;
        $this->taxonomy = $wpTerm->taxonomy;
        $this->description = $wpTerm->description;
        $this->parent = (int) $wpTerm->parent;
        $this->count = (int) $wpTerm->count;
    }

    // -------------------------------------------------------------------------

    /**
     * Reload the instance from the database.
     */
    protected function reload(): void
    {
        if ($this->termTaxonomyId === null) {
            return;
        }

        $this->loadFromTermTaxonomyId($this->termTaxonomyId);
    }
}