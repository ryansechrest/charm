<?php

namespace Charm\Contracts;

use Charm\Models\Base;
use Charm\Support\Result;

/**
 * Ensures that the model can manage meta.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasMeta
{
    /**
     * Preload missing metas in cache from database
     *
     * @return static
     */
    function preloadMetas(): static;

    /**
     * Get single (or first) meta from cache or database
     *
     * @param string $key
     * @return ?Base\Meta
     */
    function getMeta(string $key): ?Base\Meta;

    /**
     * Get metas from cache or database
     *
     * @param string $key
     * @return Base\Meta[]
     */
    function getMetas(string $key): array;

    /**
     * Create meta in cache
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    function createMeta(string $key, mixed $value): Result;

    /**
     * Create or update meta in cache
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    function updateMeta(string $key, mixed $value): Result;

    /**
     * Replace meta(s) in cache
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    function replaceMeta(string $key, mixed $value): Result;

    /**
     * Delete all metas or specified value from cache
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    function deleteMeta(string $key, mixed $value = null): Result;

    /**
     * Persist metas in database and return results
     *
     * Called by base model in `create()` and `update()`.
     *
     * @param int $objectId
     * @return Result[]
     */
    function persistMetas(int $objectId): array;
}
