<?php

namespace Charm\Traits;

use Charm\Contracts\HasDeferredCalls;
use Charm\Enums\PersistenceState;
use Charm\Models\Base\Meta;
use Charm\Support\Result;

/**
 * Adds support for managing (and caching) metadata on a model.
 *
 * A model, like `Post` or `User`, can store data beyond its core fields,
 * referred to as metadata. Each piece of metadata is a key/value pair
 * represented by another model called `Meta`.
 *
 * When a `Meta` is retrieved, created, updated, or deleted using one of the
 * methods in this trait, the change is recorded in the `$metaCache` and not
 * persisted to the database.
 *
 * Each meta tracks its own internal state (e.g., created, updated, deleted), so
 * when `persistMetas()` is called, it loops through the `$metaCache` and calls
 * `persist()` on each meta accordingly.
 *
 * If many or all metas are needed, they can be preloaded with `preloadMetas()`,
 * which fetches all metas in a single query instead of triggering multiple
 * database calls.
 *
 * Since different models store metas in their own, respective table, models
 * do not use the base `Meta` class directly, but extend it (e.g. `PostMeta`,
 * `UserMeta`).
 *
 * Therefore, each model must implement a `metaClass()` method that returns its
 * specialized meta class name, such as `PostMeta` or `UserMeta`, which
 * tells WordPress from which table to retrieve the metadata.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithMeta
{
    /**
     * Meta cache indexed by a key.
     *
     * @var array<string, Meta[]>
     */
    protected array $metaCache = [];

    // *************************************************************************

    /**
     * Returns the ID of the model.
     *
     * @return int
     */
    abstract public function getId(): int;

    /**
     * Returns the meta class name used by the model.
     *
     * Must be implemented by the model using this trait to define which
     * specialized `Meta` class (e.g. `PostMeta`, `UserMeta`) should be used.
     *
     * @return class-string<Meta>
     */
    abstract protected static function metaClass(): string;

    // *************************************************************************

    /**
     * Loads all metas from the database into the cache.
     *
     * Useful when multiple metas are expected, and it's more efficient to load
     * them all in one query rather than fetching them one at a time.
     *
     * @return static
     */
    public function preloadMetas(): static
    {
        $objectId = $this->getId();

        if ($objectId === 0) {
            return $this;
        }

        $metaClass = static::metaClass();
        $metas = $metaClass::get($objectId);

        foreach ($metas as $meta) {
            $key = $meta->getKey();
            if (!isset($this->metaCache[$key])) {
                $this->metaCache[$key] = [];
            }
            $this->metaCache[$key][] = $meta;
        }

        return $this;
    }

    // *************************************************************************

    /**
     * Returns the first meta for the given key, from cache or database.
     *
     * @param string $key
     * @return ?Meta
     */
    protected function getMeta(string $key): ?Meta
    {
        $metas = $this->getMetas($key);

        if (!isset($metas[0])) {
            return null;
        }

        return $metas[0];
    }

    /**
     * Returns all metas for the given key, from cache or database.
     *
     * If the metas are not already in the cache, they will be fetched from
     * the database.
     *
     * @param string $key
     * @return Meta[]
     */
    protected function getMetas(string $key): array
    {
        $metaClass = static::metaClass();

        if (!isset($this->metaCache[$key])) {
            $this->metaCache[$key] = $metaClass::get($this->getId(), $key);
        }

        return $this->metaCache[$key];
    }

    /**
     * Creates a new meta in the cache.
     *
     * Marks the meta as `New` and defers persistence until `persistMetas()`
     * is called.
     *
     * @param string $key
     * @param mixed $value
     * @return static
     */
    protected function createMeta(string $key, mixed $value): static
    {
        $metaClass = static::metaClass();

        $meta = new $metaClass(data: [
            'objectId' => $this->getId(),
            'metaKey' => $key,
            'metaValue' => $value,
        ]);

        $meta->mark(PersistenceState::New);

        if (!isset($this->metaCache[$key])) {
            $this->metaCache[$key] = [$meta];
            return $this;
        }

        $this->metaCache[$key][] = $meta;

        /** @var HasDeferredCalls $this */
        $this->registerDeferred(method: 'persistMetas', args: $this->getId());

        return $this;
    }

    /**
     * Updates an existing meta or creates a new one if none exists.
     *
     * Marks the meta as `Dirty` and defers persistence until `persistMetas()`
     * is called.
     *
     * @param string $key
     * @param mixed $value
     * @return static
     */
    protected function updateMeta(string $key, mixed $value): static
    {
        $meta = $this->getMeta($key);

        if ($meta === null) {
            return $this->createMeta($key, $value);
        }

        $meta->setValue($value);
        $meta->mark(PersistenceState::Dirty);

        $this->metaCache[$key][0] = $meta;

        /** @var HasDeferredCalls $this */
        $this->registerDeferred(method: 'persistMetas', args: $this->getId());

        return $this;
    }

    /**
     * Replaces all existing metas for a key with a new one.
     *
     * Deletes all existing metas for the key and creates a new meta in the
     * cache.
     *
     * @param string $key
     * @param mixed $value
     * @return static
     */
    protected function replaceMeta(string $key, mixed $value): static
    {
        $this->deleteMeta($key);
        $this->createMeta($key, $value);

        return $this;
    }

    /**
     * Marks metas for deletion from cache.
     *
     * If a value is provided, only the matching meta is deleted. If null,
     * all metas for the key are deleted. Persistence is deferred.
     *
     * @param string $key
     * @param mixed $value
     * @return static
     */
    protected function deleteMeta(string $key, mixed $value = null): static
    {
        $metas = $this->getMetas($key);

        $foundValue = false;

        foreach ($metas as $index => $meta) {

            // If no specified value, mark all metas as deleted
            if ($value === null) {
                $meta->mark(PersistenceState::Deleted);
                $this->metaCache[$key][$index] = $meta;
                continue;
            }

            // Otherwise, exit loop early if value was found
            if ($meta->getValue() === $value) {
                $meta->mark(PersistenceState::Deleted);
                $this->metaCache[$key][$index] = $meta;
                $foundValue = true;
                break;
            }
        }

        // If the specified value was not found
        if ($value !== null && !$foundValue) {
            return $this;
        }

        /** @var HasDeferredCalls $this */
        $this->registerDeferred(method: 'persistMetas', args: $this->getId());

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Persists all cached metas to the database.
     *
     * Called automatically by the base model’s `create()` or `update()`
     * method. Applies each meta’s state (`New`, `Dirty`, `Deleted`) and
     * returns an array of results.
     *
     * @param int $objectId
     * @return Result[]
     */
    protected function persistMetas(int $objectId): array
    {
        $results = [];

        if (count($this->metaCache) === 0) {
            return $results;
        }

        // Loop over every meta key in the cache
        foreach ($this->metaCache as $key => $metas) {

            // Loop over every meta value in the cache
            foreach ($metas as $index => $meta) {

                // Ensure the object ID is set
                $meta->setObjectId($objectId);

                // Persist the meta and save the result
                $results[] = $meta->persist();

                // If the meta was deleted, remove it from the cache
                if ($meta->getPersistenceState() === PersistenceState::Deleted) {
                    unset($this->metaCache[$key][$index]);
                }
            }

            // If there are no values left for the meta key, then remove the
            // key from the cache
            if (count($this->metaCache[$key]) === 0) {
                unset($this->metaCache[$key]);
            }
        }

        return $results;
    }
}