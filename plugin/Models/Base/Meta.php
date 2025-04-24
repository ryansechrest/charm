<?php

namespace Charm\Models\Base;

use Charm\Models\WordPress;
use Charm\Support\Cast;
use Charm\Support\Result;
use stdClass;

/**
 * Represents a base meta in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
abstract class Meta
{
    /**
     * Meta type
     *
     * WordPress supports: `comment`, `post`, `term`, and `user`.
     */
    protected const META_TYPE = '';

    /**
     * Meta ID field
     *
     * comment -> meta_id
     * post    -> meta_id
     * term    -> meta_id
     * user    -> umeta_id
     */
    protected const META_ID_FIELD = 'meta_id';

    /*------------------------------------------------------------------------*/

    /**
     * WordPress meta
     *
     * @var ?WordPress\Meta
     */
    protected ?WordPress\Meta $wpMeta = null;

    /**************************************************************************/

    /**
     * BaseMeta constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->wpMeta = new WordPress\Meta(static::META_TYPE, $data);
    }

    /**
     * Get WordPress meta instance
     *
     * @return ?WordPress\Meta
     */
    public function wp(): ?WordPress\Meta
    {
        return $this->wpMeta;
    }

    /**************************************************************************/

    /**
     * Initialize meta
     *
     * @param int|stdClass $key
     * @return ?static
     */
    public static function init(int|stdClass $key): ?static
    {
        if (!$wpMeta = WordPress\Meta::init(static::META_TYPE, $key)) {
            return null;
        }

        $meta = new static();
        $meta->wpMeta = $wpMeta;

        return $meta;
    }

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
        $wpMetas = WordPress\Meta::get(
            static::META_TYPE, $objectId, $metaKey
        );

        $metas = [];

        foreach ($wpMetas as $wpMeta) {
            $meta = new static();
            $meta->wpMeta = $wpMeta;
            $metas[] = $meta;
        }

        return $metas;
    }

    /**************************************************************************/

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

    /**************************************************************************/

    /**
     * Get meta ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->wp()->getMetaId() ?? 0;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get object ID
     *
     * @return int
     */
    public function getObjectId(): int
    {
        return $this->wp()->getObjectId() ?? 0;
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

    /*------------------------------------------------------------------------*/

    /**
     * Get meta key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->wp()->getMetaKey() ?? '';
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

    /*------------------------------------------------------------------------*/

    /**
     * Get meta value
     *
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->wp()->getMetaValue() ?? null;
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

    /*------------------------------------------------------------------------*/

    /**
     * Pass value to Cast
     *
     * @return Cast
     */
    public function cast(): Cast
    {
        return Cast::from($this->wp()->getMetaValue());
    }
}