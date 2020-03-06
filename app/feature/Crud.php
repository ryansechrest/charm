<?php

namespace Charm\App\Feature;

/**
 * Interface Crud
 *
 * @author Ryan Sechrest
 * @package Charm\App\Feature
 */
interface Crud
{
    /**
     * Initialize instance(s)
     *
     * @param array|int|null|object|string $key
     * @return array|null|object
     */
    public static function init($key);

    /**
     * Get instances
     *
     * @param array $params
     * @return array
     */
    public static function get(array $params): array;

    /**
     * Save instance
     *
     * @return bool
     */
    public function save(): bool;

    /**
     * Create instance
     *
     * @return bool
     */
    public function create(): bool;

    /**
     * Update instance
     *
     * @return bool
     */
    public function update(): bool;

    /**
     * Delete instance
     *
     * @return bool
     */
    public function delete(): bool;
}