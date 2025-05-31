<?php

namespace Charm\Models\Base;

use Charm\Contracts\IsPersistable;
use Charm\Contracts\Core\HasCoreMeta;
use Charm\Models\Core;
use Charm\Support\Cast;
use Charm\Support\Result;
use Charm\Traits\WithPersistenceState;

/**
 * Represents a base meta in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
abstract class Meta implements HasCoreMeta, IsPersistable
{
    use WithPersistenceState;

    // -------------------------------------------------------------------------

    /**
     * Core meta.
     *
     * @var ?Core\Meta
     */
    protected ?Core\Meta $coreMeta = null;

    // *************************************************************************

    /**
     * Ensures that the meta type gets defined.
     *
     * @return string `comment`, `post`, `term`, or `user`
     */
    abstract protected static function metaType(): string;

    // *************************************************************************

    /**
     * Meta constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->coreMeta = new Core\Meta(static::metaType(), $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Get the core meta instance.
     *
     * @return ?Core\Meta
     */
    public function coreMeta(): ?Core\Meta
    {
        return $this->coreMeta;
    }

    // *************************************************************************

    /**
     * Get the first meta for the specified key.
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
     * Get all metas or only the ones for the specified key.
     *
     * @param int $objectId
     * @param string $metaKey
     * @return static[]
     */
    public static function get(int $objectId, string $metaKey = ''): array
    {
        $coreMetas = Core\Meta::get(
            static::metaType(), $objectId, $metaKey
        );
        $metas = [];

        foreach ($coreMetas as $metaKey => $coreMeta) {

            if (!is_array($coreMeta)) {
                $meta = new static();
                $meta->coreMeta = $coreMeta;
                $metas[$metaKey] = $meta;
                continue;
            }

            foreach ($coreMeta as $singleCoreMeta) {
                $meta = new static();
                $meta->coreMeta = $singleCoreMeta;
                $metas[$metaKey][] = $meta;
            }
        }

        return $metas;
    }

    // *************************************************************************

    /**
     * Save the meta in the database.
     *
     * @return Result
     */
    public function save(): Result
    {
        return $this->coreMeta()->save();
    }

    /**
     * Create a new meta in the database.
     *
     * @return Result
     */
    public function create(): Result
    {
        return $this->coreMeta()->create();
    }

    /**
     * Update the existing meta in the database.
     *
     * @return Result
     */
    public function update(): Result
    {
        return $this->coreMeta()->update();
    }

    /**
     * Delete the meta from the database.
     *
     * @return Result
     */
    public function delete(): Result
    {
        return $this->coreMeta()->delete();
    }

    // *************************************************************************

    /**
     * Get the object ID.
     *
     * @return int
     */
    public function getObjectId(): int
    {
        return $this->coreMeta()->getObjectId();
    }

    /**
     * Set the object ID.
     *
     * @param int $objectId
     * @return static
     */
    public function setObjectId(int $objectId): static
    {
        $this->coreMeta()->setObjectId($objectId);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the meta key.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->coreMeta()->getMetaKey();
    }

    /**
     * Set the meta key.
     *
     * @param string $key
     * @return static
     */
    public function setKey(string $key): static
    {
        $this->coreMeta()->setMetaKey($key);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the meta value.
     *
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->coreMeta()->getMetaValue();
    }

    /**
     * Set the meta value.
     *
     * @param mixed $value
     * @return static
     */
    public function setValue(mixed $value): static
    {
        $this->coreMeta()->setMetaValue($value);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Cast the value to the desired data type.
     *
     * @return Cast
     */
    public function castValue(): Cast
    {
        return Cast::from($this->coreMeta()->getMetaValue());
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether the meta exists in the database.
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->coreMeta()->exists();
    }
}