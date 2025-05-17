<?php

namespace Charm\Models\Base;

use Charm\Contracts\IsPersistable;
use Charm\Contracts\Proxy\HasProxyMeta;
use Charm\Models\Proxy;
use Charm\Support\Cast;
use Charm\Support\Result;
use Charm\Traits\WithPersistenceState;

/**
 * Represents a base meta in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
abstract class Meta implements HasProxyMeta, IsPersistable
{
    use WithPersistenceState;

    // -------------------------------------------------------------------------

    /**
     * Proxy meta.
     *
     * @var ?Proxy\Meta
     */
    protected ?Proxy\Meta $proxyMeta = null;

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
        $this->proxyMeta = new Proxy\Meta(static::metaType(), $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Get the proxy meta instance.
     *
     * @return ?Proxy\Meta
     */
    public function proxyMeta(): ?Proxy\Meta
    {
        return $this->proxyMeta;
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
        $proxyMetas = Proxy\Meta::get(
            static::metaType(), $objectId, $metaKey
        );
        $metas = [];

        foreach ($proxyMetas as $metaKey => $proxyMeta) {

            if (!is_array($proxyMeta)) {
                $meta = new static();
                $meta->proxyMeta = $proxyMeta;
                $metas[$metaKey] = $meta;
                continue;
            }

            foreach ($proxyMeta as $singleProxyMeta) {
                $meta = new static();
                $meta->proxyMeta = $singleProxyMeta;
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
        return $this->proxyMeta()->save();
    }

    /**
     * Create a new meta in the database.
     *
     * @return Result
     */
    public function create(): Result
    {
        return $this->proxyMeta()->create();
    }

    /**
     * Update the existing meta in the database.
     *
     * @return Result
     */
    public function update(): Result
    {
        return $this->proxyMeta()->update();
    }

    /**
     * Delete the meta from the database.
     *
     * @return Result
     */
    public function delete(): Result
    {
        return $this->proxyMeta()->delete();
    }

    // *************************************************************************

    /**
     * Get the object ID.
     *
     * @return int
     */
    public function getObjectId(): int
    {
        return $this->proxyMeta()->getObjectId();
    }

    /**
     * Set the object ID.
     *
     * @param int $objectId
     * @return static
     */
    public function setObjectId(int $objectId): static
    {
        $this->proxyMeta()->setObjectId($objectId);

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
        return $this->proxyMeta()->getMetaKey();
    }

    /**
     * Set the meta key.
     *
     * @param string $key
     * @return static
     */
    public function setKey(string $key): static
    {
        $this->proxyMeta()->setMetaKey($key);

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
        return $this->proxyMeta()->getMetaValue();
    }

    /**
     * Set the meta value.
     *
     * @param mixed $value
     * @return static
     */
    public function setValue(mixed $value): static
    {
        $this->proxyMeta()->setMetaValue($value);

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
        return Cast::from($this->proxyMeta()->getMetaValue());
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether the meta exists in the database.
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->proxyMeta()->exists();
    }
}