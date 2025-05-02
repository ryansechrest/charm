<?php

namespace Charm\Traits;

use Charm\Enums\PersistenceState;
use Charm\Models\Base;
use Charm\Support\Result;

/**
 * Indicates that a model has meta.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithMeta
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
     * Preload missing metas in cache from database
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
     * Get single (or first) meta from cache or database
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
     * Get metas from cache or database
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
     * Create meta in cache
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

        return Result::success();
    }

    /**
     * Create or update meta in cache
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

        return Result::success();
    }

    /**
     * Replace meta(s) in cache
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
     * Delete all metas or specified value from cache
     *
     * @param string $key
     * @param mixed $value
     * @return Result
     */
    protected function deleteMeta(string $key, mixed $value = null): Result
    {
        $metas = $this->getMetas($key);

        foreach ($metas as $index => $meta) {

            if ($value === null) {
                $meta->mark(PersistenceState::DELETED);
                $this->metaCache[$key][$index] = $meta;
                continue;
            }

            if ($meta->getValue() === $value) {
                $meta->mark(PersistenceState::DELETED);
                $this->metaCache[$key][$index] = $meta;
                return Result::success();
            }
        }

        return Result::error(
            'meta_not_found', __('Meta does not exist.', 'charm')
        );
    }

    // -------------------------------------------------------------------------

    /**
     * Persist metas in database and return results
     *
     * Called by base model in `create()` and `update()`.
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