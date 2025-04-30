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
     * Get single (or first) meta by key from cache or database
     *
     * @param string $key
     * @return ?Base\Meta
     */
    function getMeta(string $key): ?Base\Meta;

    /**
     * Get all metas by key from cache or database
     *
     * @param string $key
     * @return Base\Meta[]
     */
    function getMetas(string $key): array;

    /**
     * Create or append meta to key in cache
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    function addMeta(string $key, mixed $value): Result;

    /**
     * Overwrite meta(s) by key in cache
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    function setMeta(string $key, mixed $value): Result;

    /**
     * Delete all metas or specified value by key from cache
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    function deleteMeta(string $key, mixed $value = null): Result;
}
