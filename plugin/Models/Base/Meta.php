<?php

namespace Charm\Models\Base;

use Charm\Contracts\HasWpMeta;
use Charm\Contracts\IsPersistable;
use Charm\Models\WordPress;
use Charm\Support\Cast;
use Charm\Support\Result;
use Charm\Traits\WithPersistenceState;

/**
 * Represents a base meta in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
abstract class Meta implements HasWpMeta, IsPersistable
{
    use WithPersistenceState;

    // -------------------------------------------------------------------------

    /**
     * WordPress meta
     *
     * @var ?WordPress\Meta
     */
    protected ?WordPress\Meta $wpMeta = null;

    // *************************************************************************

    /**
     * Force meta type definition
     *
     * e.g. `comment`, `post`, `term`, and `user`
     *
     * @return string
     */
    abstract protected static function metaType(): string;

    // *************************************************************************

    /**
     * Meta constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->wpMeta = new WordPress\Meta(static::metaType(), $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Get WordPress meta instance
     *
     * @return ?WordPress\Meta
     */
    public function wp(): ?WordPress\Meta
    {
        return $this->wpMeta;
    }

    // *************************************************************************

    /**
     * Get first meta by specified key
     *
     * @param int $objectId
     * @param string $metaKey
     * @return ?static
     */
    public static function getFirst(int $objectId, string $metaKey): ?static
    {
        $metas = static::get($objectId, $metaKey);

        if (!isset($metas[0])) {
            return null;
        }

        return $metas[0];
    }

    /**
     * Get all metas or by specified key
     *
     * @param int $objectId
     * @param string $metaKey
     * @return static[]
     */
    public static function get(int $objectId, string $metaKey = ''): array
    {
        $wpMetas = WordPress\Meta::get(static::metaType(), $objectId, $metaKey);

        $metas = [];

        foreach ($wpMetas as $metaKey => $wpMeta) {

            if (!is_array($wpMeta)) {
                $meta = new static();
                $meta->wpMeta = $wpMeta;
                $metas[$metaKey] = $meta;
                continue;
            }

            foreach ($wpMeta as $wpSingleMeta) {
                $meta = new static();
                $meta->wpMeta = $wpSingleMeta;
                $metas[$metaKey][] = $meta;
            }

        }

        return $metas;
    }

    // *************************************************************************

    /**
     * Save meta
     *
     * @return Result
     */
    public function save(): Result
    {
        return $this->wp()->save();
    }

    /**
     * Create new meta
     *
     * @return Result
     */
    public function create(): Result
    {
        return $this->wp()->create();
    }

    /**
     * Update existing meta
     *
     * @return Result
     */
    public function update(): Result
    {
        return $this->wp()->update();
    }

    /**
     * Delete meta
     *
     * @return Result
     */
    public function delete(): Result
    {
        return $this->wp()->delete();
    }

    // *************************************************************************

    /**
     * Get object ID
     *
     * @return int
     */
    public function getObjectId(): int
    {
        return $this->wp()->getObjectId();
    }

    /**
     * Set object ID
     *
     * @param int $objectId
     * @return static
     */
    public function setObjectId(int $objectId): static
    {
        $this->wp()->setObjectId($objectId);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get meta key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->wp()->getMetaKey();
    }

    /**
     * Set meta key
     *
     * @param string $key
     * @return static
     */
    public function setKey(string $key): static
    {
        $this->wp()->setMetaKey($key);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get meta value
     *
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->wp()->getMetaValue();
    }

    /**
     * Set meta value
     *
     * @param mixed $value
     * @return static
     */
    public function setValue(mixed $value): static
    {
        $this->wp()->setMetaValue($value);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Cast value to specified data type
     *
     * @return Cast
     */
    public function castValue(): Cast
    {
        return Cast::from($this->wp()->getMetaValue());
    }
}