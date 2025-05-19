<?php

namespace Charm\Contracts;

use Charm\Models\Base;
use Charm\Support\Result;

/**
 * Ensures that the model can manage metadata.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasMeta
{
    /**
     * Loads all metas from the database into the cache.
     *
     * Useful when accessing multiple metas, because it's more efficient to
     * load them in one query rather than fetching them one at a time.
     *
     * @return static
     */
    public function preloadMetas(): static;

    // *************************************************************************

    /**
     * Returns the first meta for the given key from the cache or database.
     *
     * @param string $key
     * @return ?Base\Meta
     */
    function getMeta(string $key): ?Base\Meta;

    /**
     * Returns all metas for the given key from the cache or database.
     *
     * If the metas are not already in the cache, they will be fetched from
     * the database and then cached.
     *
     * @param string $key
     * @return Base\Meta[]
     */
    function getMetas(string $key): array;

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
    function createMeta(string $key, mixed $value): Result;

    /**
     * Updates an existing meta or creates a new one if it doesn't exist.
     *
     * Marks the meta as `DIRTY` and defers persistence until `persistMetas()`
     * is called.
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    function updateMeta(string $key, mixed $value): Result;

    /**
     * Replaces all existing metas for a key with the new one.
     *
     * Deletes all existing metas for the key and creates a new meta in the
     * cache.
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    function replaceMeta(string $key, mixed $value): Result;

    /**
     * Marks metas for deletion in the cache.
     *
     * If a value is provided, only the matching meta is deleted, but ff null
     * is provided, then all metas for the key are deleted.
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    function deleteMeta(string $key, mixed $value = null): Result;

    // -------------------------------------------------------------------------

    /**
     * Persists all cached metas in the database.
     *
     * Called automatically by the base model’s `create()` or `update()`
     * method. Applies each meta’s state (`NEW`, `DIRTY`, `DELETED`) and
     * returns an array of results.
     *
     * @param int $objectId
     * @return Result[]
     */
    function persistMetas(int $objectId): array;
}
