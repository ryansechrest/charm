<?php

namespace Charm\Models\Proxy;

use Charm\Contracts\IsPersistable;
use Charm\Support\Result;

/**
 * Represents a proxy meta belonging to any model in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Meta implements IsPersistable
{
    /**
     * Meta type
     *
     * @var string `comment`, `post`, `term`, or `user`
     */
    protected string $metaType = '';

    /**
     * Meta ID field
     *
     * @var string `meta_id` or `umeta_id` (if $metaType === `user`)
     */
    protected string $metaIdField = '';

    // -------------------------------------------------------------------------

    /**
     * Object ID
     *
     * @var ?int
     */
    protected ?int $objectId = null;

    /**
     * Meta key
     *
     * @var ?string
     */
    protected ?string $metaKey = null;

    /**
     * Meta value
     *
     * @var mixed
     */
    protected mixed $metaValue = null;

    /**
     * Previous meta value
     *
     * @var mixed
     */
    protected mixed $prevMetaValue = null;

    // -------------------------------------------------------------------------

    /**
     * Whether meta exists in database
     *
     * @var bool
     */
    protected bool $exists = false;

    // *************************************************************************

    /**
     * Meta constructor
     *
     * @param string $metaType
     * @param array $data
     */
    public function __construct(string $metaType, array $data = [])
    {
        $this->metaType = $metaType;
        $this->metaIdField = static::getMetaIdField($metaType);

        $this->load($data);
    }

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['objectId'])) {
            $this->objectId = (int) $data['objectId'];
        }

        if (isset($data['metaKey'])) {
            $this->metaKey = $data['metaKey'];
        }

        if (isset($data['metaValue'])) {
            $this->metaValue = $data['metaValue'];
        }

        if (isset($data['prevMetaValue'])) {
            $this->prevMetaValue = $data['prevMetaValue'];
        }

        if (isset($data['exists'])) {
            $this->exists = (bool) $data['exists'];
        }
    }

    // *************************************************************************

    /**
     * Get all metas or by specified key
     *
     * @param string $metaType
     * @param int $objectId
     * @param string $metaKey
     * @return static[]
     * @see get_metadata()
     */
    public static function get(
        string $metaType, int $objectId, string $metaKey = ''
    ): array
    {
        // Get either all metas or metas that match specified key
        $metaValues = get_metadata($metaType, $objectId, $metaKey);

        // If get_metadata() determined object ID is invalid
        if ($metaValues ===  false) {
            return [];
        }

        // If no metas were returned
        if (is_array($metaValues) && count($metaValues) === 0) {
            return [];
        }

        // If no meta key provided, assume we need to load array of arrays
        if ($metaKey === '') {
            return static::getAll($metaType, $objectId, $metaValues);
        }

        // Otherwise, assume specified meta key can occur more than once
        return static::getMultiple($metaType, $objectId, $metaKey, $metaValues);
    }

    /**
     * Get first meta by specified key
     *
     * @param string $metaType
     * @param int $objectId
     * @param string $metaKey
     * @return ?static
     */
    public static function getFirst(
        string $metaType, int $objectId, string $metaKey
    ): ?static
    {
        $metas = static::get($metaType, $objectId, $metaKey);

        if (!isset($metas[0])) {
            return null;
        }

        return $metas[0];
    }

    // -------------------------------------------------------------------------

    /**
     * Create meta with specified key and value on object
     *
     * @param string $metaType
     * @param int $objectId
     * @param string $metaKey
     * @param mixed $metaValue
     * @return Result
     * @see add_metadata()
     */
    public static function createMeta(
        string $metaType,
        int $objectId,
        string $metaKey,
        mixed $metaValue
    ): Result
    {
        $result = add_metadata(
            $metaType,
            $objectId,
            $metaKey,
            $metaValue
        );

        if ($result === false) {
            return Result::error(
                code: 'add_metadata_failed',
                message: __('add_metadata() returned false.', 'charm')
            )->withData(func_get_args());
        }

        return Result::success();
    }

    /**
     * Update meta with specified key and value on object
     *
     * @param string $metaType
     * @param int $objectId
     * @param string $metaKey
     * @param mixed $metaValue
     * @param mixed $prevMetaValue
     * @return Result
     * @see update_metadata()
     */
    public static function updateMeta(
        string $metaType,
        int $objectId,
        string $metaKey,
        mixed $metaValue,
        mixed $prevMetaValue = null
    ): Result
    {
        $result = update_metadata(
            $metaType,
            $objectId,
            $metaKey,
            $metaValue,
            $prevMetaValue
        );

        if ($result === false) {
            return Result::error(
                code: 'update_metadata_failed',
                message: __('update_metadata() returned false.', 'charm')
            )->withData(func_get_args());
        }

        return Result::success();
    }

    /**
     * Delete meta with specified key (and optional value) from object
     *
     * @param string $metaType
     * @param int $objectId
     * @param string $metaKey
     * @param mixed $metaValue
     * @return Result
     * @see delete_metadata()
     */
    public static function deleteMeta(
        string $metaType, int $objectId, string $metaKey, mixed $metaValue = ''
    ): Result
    {
        $result = delete_metadata($metaType, $objectId, $metaKey, $metaValue);

        if ($result === false) {
            return Result::error(
                code: 'delete_metadata_failed',
                message: __('delete_metadata() returned false.', 'charm')
            )->withData(func_get_args());
        }

        return Result::success();
    }

    /**
     * Purge meta with specified key (and optional value) from ALL objects
     *
     * @param string $metaType
     * @param string $metaKey
     * @param string $metaValue
     * @return Result
     * @see delete_metadata()
     */
    public static function purgeMeta(
        string $metaType, string $metaKey, string $metaValue = ''
    ): Result
    {
        $result = delete_metadata(
            meta_type: $metaType,
            object_id: 0,
            meta_key: $metaKey,
            meta_value: $metaValue,
            delete_all: true
        );

        if ($result === false) {
            return Result::error(
                code: 'delete_metadata_failed',
                message: __('delete_metadata() returned false.', 'charm')
            )->withData(func_get_args());
        }

        return Result::success();
    }

    /**
     * Whether meta with specified key (and optional value) exists on object
     *
     * @param string $metaType
     * @param int $objectId
     * @param string $metaKey
     * @return bool
     * @see metadata_exists()
     */
    public static function hasMeta(
        string $metaType, int $objectId, string $metaKey
    ): bool
    {
        return metadata_exists($metaType, $objectId, $metaKey);
    }

    // *************************************************************************

    /**
     * Save meta
     *
     * @return Result
     */
    public function save(): Result
    {
        return !$this->exists ? $this->create() : $this->update();
    }

    /**
     * Create new meta
     *
     * @return Result
     */
    public function create(): Result
    {
        if ($this->exists) {
            return Result::error(
                code: 'meta_exists',
                message: __('Meta already exists.', 'charm')
            )->withData($this);
        }

        $result = static::createMeta(
            metaType: $this->metaType,
            objectId: $this->objectId,
            metaKey: $this->metaKey,
            metaValue: $this->metaValue
        );

        if ($result->hasFailed()) {
            return $result;
        }

        $this->exists = true;

        return $result;
    }

    /**
     * Update existing meta
     *
     * @return Result
     */
    public function update(): Result
    {
        if (!$this->exists) {
            return Result::error(
                code: 'meta_missing',
                message: __('Cannot update meta that does not exist.', 'charm')
            )->withData($this);
        }

        $result = static::updateMeta(
            metaType: $this->metaType,
            objectId: $this->objectId,
            metaKey: $this->metaKey,
            metaValue: $this->metaValue,
            prevMetaValue: $this->prevMetaValue
        );

        if ($result->hasFailed()) {
            return $result;
        }

        $this->prevMetaValue = $this->metaValue;

        return $result;
    }

    /**
     * Delete meta
     *
     * @return Result
     */
    public function delete(): Result
    {
        if (!$this->exists) {
            return Result::error(
                code: 'meta_missing',
                message: __('Cannot delete meta that does not exist.', 'charm')
            )->withData($this);
        }

        $result = static::deleteMeta(
            metaType: $this->metaType,
            objectId: $this->objectId,
            metaKey: $this->metaKey,
            metaValue: $this->metaValue
        );

        if ($result->hasFailed()) {
            return $result;
        }

        $this->exists = false;

        return $result;
    }

    // *************************************************************************

    /**
     * Get meta type
     *
     * @return string
     */
    public function getMetaType(): string
    {
        return $this->metaType ?? '';
    }

    // -------------------------------------------------------------------------

    /**
     * Get object ID
     *
     * @return int
     */
    public function getObjectId(): int
    {
        return $this->objectId ?? 0;
    }

    /**
     * Set object ID
     *
     * @param int $objectId
     * @return static
     */
    public function setObjectId(int $objectId): static
    {
        $this->objectId = $objectId;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get meta key
     *
     * @return string
     */
    public function getMetaKey(): string
    {
        return $this->metaKey ?? '';
    }

    /**
     * Set meta key
     *
     * @param string $metaKey
     * @return static
     */
    public function setMetaKey(string $metaKey): static
    {
        $this->metaKey = $metaKey;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get meta value
     *
     * @return mixed
     */
    public function getMetaValue(): mixed
    {
        return $this->metaValue ?? null;
    }

    /**
     * Set meta value
     *
     * @param mixed $metaValue
     * @return static
     */
    public function setMetaValue(mixed $metaValue): static
    {
        $this->metaValue = $metaValue;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Whether meta exists in database
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->exists;
    }

    /**
     * Whether meta has changed
     *
     * @return bool
     */
    public function hasChanged(): bool
    {
        return $this->metaValue !== $this->prevMetaValue;
    }

    // *************************************************************************

    /**
     * Get all metas
     *
     * @param string $metaType
     * @param int $objectId
     * @param array $metaValues
     * @return static[]
     */
    protected static function getAll(
        string $metaType, int $objectId, array $metaValues
    ): array {
        $metas = [];

        // Loop over every meta with different key
        foreach ($metaValues as $metaKey => $metaValue) {

            // They should all be arrays, even if just one item
            if (!is_array($metaValue)) {
                continue;
            }

            // If array contains one item, save it and move on
            if (count($metaValue) === 1 && isset($metaValue[0])) {
                $metas[$metaKey] = self::getSingle(
                    $metaType, $objectId, $metaKey, $metaValue[0]
                );
                continue;
            }

            // Otherwise, if array contains multiple items with same key,
            // put them in an array with that key
            $metas[$metaKey] = self::getMultiple(
                $metaType, $objectId, $metaKey, $metaValue
            );
        }

        return $metas;
    }

    /**
     * Get meta with multiple, identical keys
     *
     * @param string $metaType
     * @param int $objectId
     * @param string $metaKey
     * @param array $metaValues
     * @return static[]
     */
    protected static function getMultiple(
        string $metaType, int $objectId, string $metaKey, array $metaValues
    ): array {
        $metas = [];

        // Loop over every meta with same key
        foreach ($metaValues as $metaValue) {
            $metas[] = self::getSingle(
                $metaType, $objectId, $metaKey, $metaValue
            );
        }

        return $metas;
    }

    /**
     * Get meta with single key and value
     *
     * @param string $metaType
     * @param int $objectId
     * @param string $metaKey
     * @param mixed $metaValue
     * @return static
     * @see maybe_unserialize()
     */
    protected static function getSingle(
        string $metaType, int $objectId, string $metaKey, mixed $metaValue
    ): static {
        $metaValue = maybe_unserialize($metaValue);

        return new static($metaType, [
            'objectId' => $objectId,
            'metaKey' => $metaKey,
            'metaValue' => $metaValue,
            'prevMetaValue' => $metaValue,
            'exists' => true,
        ]);
    }

    // -------------------------------------------------------------------------

    /**
     * Get meta ID field based on type
     *
     * @param string $metaType
     * @return string
     */
    protected static function getMetaIdField(string $metaType): string
    {
        return $metaType === 'user' ? 'umeta_id' : 'meta_id';
    }
}