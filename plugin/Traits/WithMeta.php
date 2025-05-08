<?php

namespace Charm\Traits;

use Charm\Contracts\HasDeferredCalls;
use Charm\Enums\PersistenceState;
use Charm\Models\Base;
use Charm\Support\Result;

/**
 * Adds support for managing (and caching) meta data on a model.
 *
 * A model, like `Post` or `User`, can store data beyond its core fields,
 * referred to as meta data. Each piece of meta data is a key/value pair
 * represented by another model called `Meta`.
 *
 * When a `Meta` is retrieved, created, updated, or deleted using one of the
 * methods in this trait, the change is recorded in the `$metaCache` and not
 * persisted to the database.
 *
 * Each meta tracks its own internal state (e.g. created, updated, deleted), so
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
 * tells WordPress from which table to retrieve the meta data.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithMeta
{
    /**
     * Meta cache indexed by key.
     *
     * @var array<string, Base\Meta[]>
     */
    protected array $metaCache = [];

    // *************************************************************************

    /**
     * Returns the meta class name used by the model.
     *
     * Must be implemented by the model using this trait to define which
     * specialized `Meta` class (e.g. `PostMeta`, `UserMeta`) should be used.
     *
     * @return class-string<Base\Meta>
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
     * @return ?Base\Meta
     */
    protected function getMeta(string $key): ?Base\Meta
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
     * If the metas are not already in cache, they will be fetched from the
     * database.
     *
     * @param string $key
     * @return Base\Meta[]
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
     * Marks the meta as `NEW` and defers persistence until `persistMetas()`
     * is called.
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    protected function createMeta(string $key, mixed $value): Result
    {
        $metaClass = static::metaClass();

        $meta = new $metaClass([
            'objectId' => $this->getId(),
            'metaKey' => $key,
            'metaValue' => $value,
        ]);

        $meta->mark(PersistenceState::NEW);

        if (!isset($this->metaCache[$key])) {
            $this->metaCache[$key] = [$meta];
            return Result::success();
        }

        $this->metaCache[$key][] = $meta;

        /** @var HasDeferredCalls $this */
        $this->registerDeferred('persistMetas', $this->getId());

        return Result::success();
    }

    /**
     * Updates an existing meta or creates a new one if none exists.
     *
     * Marks the meta as `DIRTY` and defers persistence until `persistMetas()`
     * is called.
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    protected function updateMeta(string $key, mixed $value): Result
    {
        $meta = $this->getMeta($key);

        if ($meta === null) {
            return $this->createMeta($key, $value);
        }

        $meta->setValue($value);
        $meta->mark(PersistenceState::DIRTY);

        $this->metaCache[$key][0] = $meta;

        /** @var HasDeferredCalls $this */
        $this->registerDeferred('persistMetas', $this->getId());

        return Result::success();
    }

    /**
     * Replaces all existing metas for a key with a new one.
     *
     * Deletes all existing metas for the key and creates a new meta in the
     * cache.
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    protected function replaceMeta(string $key, mixed $value): Result
    {
        $this->deleteMeta($key);
        $this->createMeta($key, $value);

        return Result::success();
    }

    /**
     * Marks metas for deletion from cache.
     *
     * If a value is provided, only the matching meta is deleted. If null,
     * all metas for the key are deleted. Persistence is deferred.
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    protected function deleteMeta(string $key, mixed $value = null): Result
    {
        $metas = $this->getMetas($key);

        $foundValue = false;

        foreach ($metas as $index => $meta) {

            // If no specified value, mark all metas as deleted
            if ($value === null) {
                $meta->mark(PersistenceState::DELETED);
                $this->metaCache[$key][$index] = $meta;
                continue;
            }

            // Otherwise, exit loop early if value was found
            if ($meta->getValue() === $value) {
                $meta->mark(PersistenceState::DELETED);
                $this->metaCache[$key][$index] = $meta;
                $foundValue = true;
                break;
            }
        }

        // If specified value was not found
        if ($value !== null && !$foundValue) {
            return Result::error(
                'meta_not_found', __('Meta does not exist.', 'charm')
            )->withData($this);
        }

        /** @var HasDeferredCalls $this */
        $this->registerDeferred('persistMetas', $this->getId());

        return Result::success();
    }

    // -------------------------------------------------------------------------

    /**
     * Persists all cached metas to the database.
     *
     * Called automatically by the base model’s `create()` or `update()`
     * method. Applies each meta’s state (`NEW`, `DIRTY`, `DELETED`) and
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

        // Loop over every meta key in cache
        foreach ($this->metaCache as $key => $metas) {

            // Loop over every meta value in cache
            foreach ($metas as $index => $meta) {

                // Ensure object ID is set
                $meta->setObjectId($objectId);

                // Persist meta and save result
                $results[] = $meta->persist();

                // If meta was deleted, remove from cache
                if ($meta->getPersistenceState() === PersistenceState::DELETED) {
                    unset($this->metaCache[$key][$index]);
                }
            }

            // If not values left for meta key, remove key from cache
            if (count($this->metaCache[$key]) === 0) {
                unset($this->metaCache[$key]);
            }
        }

        return $results;
    }
}