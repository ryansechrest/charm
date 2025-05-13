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
     * Proxy meta
     *
     * @var ?Proxy\Meta
     */
    protected ?Proxy\Meta $proxyMeta = null;

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
        $this->proxyMeta = new Proxy\Meta(static::metaType(), $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Get proxy meta instance
     *
     * @return ?Proxy\Meta
     */
    public function proxyMeta(): ?Proxy\Meta
    {
        return $this->proxyMeta;
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
     * Save meta
     *
     * @return Result
     */
    public function save(): Result
    {
        return $this->proxyMeta()->save();
    }

    /**
     * Create new meta
     *
     * @return Result
     */
    public function create(): Result
    {
        return $this->proxyMeta()->create();
    }

    /**
     * Update existing meta
     *
     * @return Result
     */
    public function update(): Result
    {
        return $this->proxyMeta()->update();
    }

    /**
     * Delete meta
     *
     * @return Result
     */
    public function delete(): Result
    {
        return $this->proxyMeta()->delete();
    }

    // *************************************************************************

    /**
     * Get object ID
     *
     * @return int
     */
    public function getObjectId(): int
    {
        return $this->proxyMeta()->getObjectId();
    }

    /**
     * Set object ID
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
     * Get meta key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->proxyMeta()->getMetaKey();
    }

    /**
     * Set meta key
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
     * Get meta value
     *
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->proxyMeta()->getMetaValue();
    }

    /**
     * Set meta value
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
     * Cast value to specified data type
     *
     * @return Cast
     */
    public function castValue(): Cast
    {
        return Cast::from($this->proxyMeta()->getMetaValue());
    }

    // -------------------------------------------------------------------------

    /**
     * Whether meta exists in database
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->proxyMeta()->exists();
    }
}