<?php

namespace Charm\Traits\Metas;

use Charm\Contracts\IsPersistable;
use Charm\Enums\PersistenceState;
use Charm\Models\Base;
use Charm\Support\Result;

/**
 * Indicates that a model has meta.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasMeta
{
    /**
     * Meta cache
     *
     * @var array<string, Base\Meta[]>
     */
    protected array $metaCache = [];

    // *************************************************************************

    /**
     * Force meta class definition
     *
     * @return class-string<Base\Meta>
     */
    abstract protected static function metaClass(): string;

    // *************************************************************************

    /**
     * Get single (or first) meta by key from cache or database
     *
     * @param string $key
     * @return ?Base\Meta
     */
    protected function getMeta(string $key): Base\Meta|null
    {
        // Get all metas by key from cache or database
        $metas = $this->getMetas($key);

        // If no metas exist, return null
        if (!isset($metas[0])) {
            return null;
        }

        // Otherwise, return first (and maybe only) meta
        return $metas[0];
    }

    /**
     * Get all metas by key from cache or database
     *
     * @param string $key
     * @return Base\Meta[]
     */
    protected function getMetas(string $key): array
    {
        /** @var IsPersistable $this */

        // Class to use for building up metas
        $metaClass = static::metaClass();

        // If metas are not cached, fetch and cache them
        if (!isset($this->metaCache[$key])) {
            $this->metaCache[$key] = $metaClass::get($this->getId(), $key);
        }

        // Filter out metas marked as deleted
        return array_values(
            array_filter(
                $this->metaCache[$key],
                fn(Base\Meta $meta) => $meta->getPersistenceState() !== PersistenceState::DELETED
            )
        );
    }

    /**
     * Create or append meta to key in cache
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    protected function addMeta(string $key, mixed $value): Result
    {
        /** @var IsPersistable $this */

        // Class to use for building up metas
        $metaClass = static::metaClass();

        // Create new meta
        $meta = new $metaClass([
            'objectId' => $this->getId(),
            'metaKey' => $key,
            'metaValue' => $value,
        ]);

        // Mark it as new so it can be persisted later
        $meta->mark(PersistenceState::NEW);

        // If key doesn't exist...
        // Create array with new meta
        if (!isset($this->metaCache[$key])) {
            $this->metaCache[$key] = [$meta];
            return Result::success();
        }

        // Otherwise, add new meta to existing array
        $this->metaCache[$key][] = $meta;

        return Result::success();
    }

    /**
     * Overwrite meta(s) by key in cache
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    protected function setMeta(string $key, mixed $value): Result
    {
        // Mark existing metas as deleted so they can be persisted later
        $this->deleteMeta($key);

        // Append new meta to key in cache
        $this->addMeta($key, $value);

        return Result::success();
    }

    /**
     * Delete all metas or specified value by key from cache
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    protected function deleteMeta(string $key, mixed $value = null): Result
    {
        // Get all metas by key from cache or database
        $metas = $this->getMetas($key);

        // If no specific value is targeted
        if ($value === null) {

            // Loop over each meta in key
            foreach ($metas as $index => $meta) {

                // Mark it as deleted so it can be persisted later
                $meta->mark(PersistenceState::DELETED);

                // Save updated meta in cache
                $this->metaCache[$key][$index] = $meta;
            }

            return Result::success();
        }

        $index = 0;
        $found = false;

        do {

            // Alias for current meta
            $meta = $metas[$index];

            // If meta is not target, keep looking
            if ($meta->getValue() !== $value) {
                $index++;
                continue;
            }

            // Otherwise, we found target
            $found = true;

            // Mark it as deleted so it can be persisted later
            $meta->mark(PersistenceState::DELETED);

            // Save updated meta in cache
            $this->metaCache[$key][$index] = $meta;

        } while (!$found && $index < count($metas));

        return $found ? Result::success() : Result::error(
            'meta_not_found', __('Meta does not exist.', 'charm')
        );
    }

    // -------------------------------------------------------------------------

    /**
     * Persist metas in database and return results
     *
     * @return Result[]
     */
    protected function persistMetas(): array
    {
        $results = [];

        // Loop over each meta key in cache
        foreach ($this->metaCache as $key => $metas) {

            // Loop over each meta for key
            foreach ($metas as $index => $meta) {

                // Persist meta in database and save result
                $results[] = $meta->persist();

                // If meta was deleted, remove it from cache
                if ($meta->getPersistenceState() === PersistenceState::DELETED) {
                    unset($this->metaCache[$key][$index]);
                }
            }

            // If no metas exist for key, remove key from cache
            if (count($this->metaCache[$key]) === 0) {
                unset($this->metaCache[$key]);
            }
        }

        return $results;
    }

    // *************************************************************************

    /**
     * Fill metas with object ID
     *
     * @param int $objectId
     * @return void
     */
    private function fillMetasWithObjectId(int $objectId): void
    {
        /** @var IsPersistable $this */

        // Loop over each meta key in cache
        foreach ($this->metaCache as $metas) {

            // Loop over each meta for key
            foreach ($metas as $meta) {

                // Set current object ID
                $meta->setObjectId($objectId);
            }
        }
    }
}