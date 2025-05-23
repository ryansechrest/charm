<?php

namespace Charm\Models\Proxy;

use Charm\Contracts\IsArrayable;
use Charm\Contracts\IsPersistable;
use Charm\Enums\Result\Message;
use Charm\Support\Result;
use Charm\Traits\WithToArray;

/**
 * Represents a proxy meta belonging to any model in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Meta implements IsArrayable, IsPersistable
{
    use WithToArray;

    // *************************************************************************

    /**
     * Meta type.
     *
     * @var string `comment`, `post`, `term`, or `user`
     */
    protected string $metaType = '';

    /**
     * Meta ID field.
     *
     * @var string `meta_id` or `umeta_id` (if $metaType === `user`)
     */
    protected string $metaIdField = '';

    // -------------------------------------------------------------------------

    /**
     * Object ID.
     *
     * @var ?int
     */
    protected ?int $objectId = null;

    /**
     * Meta key.
     *
     * @var ?string
     */
    protected ?string $metaKey = null;

    /**
     * Meta value.
     *
     * @var mixed
     */
    protected mixed $metaValue = null;

    /**
     * Previous meta value.
     *
     * @var mixed
     */
    protected mixed $prevMetaValue = null;

    // -------------------------------------------------------------------------

    /**
     * Whether the meta exists in the database.
     *
     * @var bool
     */
    protected bool $exists = false;

    // *************************************************************************

    /**
     * Meta constructor.
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
     * Load the instance with data.
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
     * Get the first meta for the specified key.
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

    /**
     * Get all metas or only the ones for the specified key.
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
        // Get either all metas or metas that match the specified key
        $metaValues = get_metadata($metaType, $objectId, $metaKey);

        // If `get_metadata()` determined that the object ID is invalid
        if ($metaValues ===  false) {
            return [];
        }

        // If no metas were returned
        if (is_array($metaValues) && count($metaValues) === 0) {
            return [];
        }

        // If no meta key is provided, assume we need to load an array of arrays
        if ($metaKey === '') {
            return static::getAll($metaType, $objectId, $metaValues);
        }

        // Otherwise, assume the specified meta key can occur more than once
        return static::getMultiple($metaType, $objectId, $metaKey, $metaValues);
    }

    // -------------------------------------------------------------------------

    /**
     * Create a meta for that object in the database.
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
        // `int`  -> Meta ID -> Success: Meta created
        // `bool` -> `false` -> Fail: Meta not created
        $result = add_metadata(
            $metaType,
            $objectId,
            $metaKey,
            $metaValue
        );

        if ($result === false) {
            return Result::error(
                'meta_create_failed',
                'Meta could not be created. `add_metadata()` returned `false`.'
            )->withReturn($result)->withData(func_get_args());
        }

        if (!is_int($result)) {
            return Result::error(
                'meta_create_failed',
                'Meta could not be created. Expected `add_metadata()` to return `true`, but received an unexpected result.'
            )->withReturn($result)->withData(func_get_args());
        }

        return Result::success(
            'meta_create_success',
            'Meta successfully created.'
        )->withId($result)->withReturn($result)->withData(func_get_args());
    }

    /**
     * Update a meta for that object in the database.
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
        // `int`  -> Meta ID -> Success: New meta created
        // `bool` -> `true`  -> Success: Existing meta updated
        // `bool` -> `false` -> Fail: Meta not updated or value already exists
        $result = update_metadata(
            $metaType,
            $objectId,
            $metaKey,
            $metaValue,
            $prevMetaValue
        );

        if ($result === false) {
            return Result::error(
                'meta_update_failed',
                'Meta could not be updated. `update_metadata()` returned `false`.'
            )->withReturn($result)->withData(func_get_args());
        }

        if (is_int($result)) {
            return Result::info(
                'meta_update_create',
                'Meta did not exist, so it was created instead.'
            )->withId($result)->withReturn($result)->withData(func_get_args());
        }

        if ($result !== true) {
            return Result::error(
                'meta_update_failed',
                'Meta could not be updated. Expected `update_metadata()` to return `true`, but received an unexpected result.'
            )->withReturn($result)->withData(func_get_args());
        }

        return Result::success(
            'meta_update_success',
            'Meta successfully updated.'
        )->withReturn($result)->withData(func_get_args());
    }

    /**
     * Delete a meta (with optional value) for that object in the database.
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
        // `bool` -> `true`  -> Success: Meta deleted
        // `bool` -> `false` -> Fail: Meta not deleted
        $result = delete_metadata($metaType, $objectId, $metaKey, $metaValue);

        if ($result === false) {
            return Result::error(
                'meta_delete_failed',
                'Meta could not be deleted. `delete_metadata()` returned `false`.'
            )->withReturn($result)->withData(func_get_args());
        }

        if ($result !== true) {
            return Result::error(
                'meta_delete_failed',
                'Meta could not be deleted. Expected `delete_metadata()` to return `true`, but received an unexpected result.'
            )->withReturn($result)->withData(func_get_args());
        }

        return Result::success(
            'meta_delete_success',
            'Meta successfully deleted.'
        )->withReturn($result)->withData(func_get_args());
    }

    /**
     * Purge all the metas with the specified key (and optional value) from ALL
     * objects in the database.
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
        // `bool` -> `true`  -> Success: Metas deleted
        // `bool` -> `false` -> Fail: Metas not deleted
        $result = delete_metadata(
            meta_type: $metaType,
            object_id: 0,
            meta_key: $metaKey,
            meta_value: $metaValue,
            delete_all: true
        );

        if ($result === false) {
            return Result::error(
                'meta_purge_failed',
                'Metas could not be purged. `delete_metadata()` returned `false`.'
            )->withReturn($result)->withData(func_get_args());
        }

        if ($result !== true) {
            return Result::error(
                'meta_purge_failed',
                'Metas could not be purged. Expected `delete_metadata()` to return `true`, but received an unexpected result.'
            )->withReturn($result)->withData(func_get_args());
        }

        return Result::success(
            'meta_purge_success',
            'Metas successfully purged.'
        )->withReturn($result)->withData(func_get_args());
    }

    /**
     * Check whether the meta with the specified key (and optional value) exists
     * for that object in the database.
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
     * Save the meta in the database.
     *
     * @return Result
     */
    public function save(): Result
    {
        return !$this->exists ? $this->create() : $this->update();
    }

    /**
     * Create the meta in the database.
     *
     * @return Result
     */
    public function create(): Result
    {
        if ($this->exists) {
            return Result::error(
                'meta_already_exists',
                'Meta was not created because it already exists.'
            )->withData($this->toArray());
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
     * Update the meta in the database.
     *
     * @return Result
     */
    public function update(): Result
    {
        if (!$this->exists) {
            return Result::error(
                'meta_not_exists',
                'Meta was not updated because it does not exist.'
            )->withData($this->toArray());
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
     * Delete the meta from the database.
     *
     * @return Result
     */
    public function delete(): Result
    {
        if (!$this->exists) {
            return Result::error(
                'meta_not_exists',
                'Meta was not deleted because it does not exist.'
            )->withData($this->toArray());
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
     * Get the meta type.
     *
     * @return string
     */
    public function getMetaType(): string
    {
        return $this->metaType ?? '';
    }

    // -------------------------------------------------------------------------

    /**
     * Get the object ID.
     *
     * @return int
     */
    public function getObjectId(): int
    {
        return $this->objectId ?? 0;
    }

    /**
     * Set the object ID.
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
     * Get the meta key.
     *
     * @return string
     */
    public function getMetaKey(): string
    {
        return $this->metaKey ?? '';
    }

    /**
     * Set the meta key.
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
     * Get the meta value.
     *
     * @return mixed
     */
    public function getMetaValue(): mixed
    {
        return $this->metaValue ?? null;
    }

    /**
     * Set the meta value.
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
     * Check whether the meta exists in the database.
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->exists;
    }

    /**
     * Check whether the meta has changed since it was retrieved from the
     * database.
     *
     * @return bool
     */
    public function hasChanged(): bool
    {
        return $this->metaValue !== $this->prevMetaValue;
    }

    // *************************************************************************

    /**
     * Get all the metas.
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

        // Loop over every meta with a different key
        foreach ($metaValues as $metaKey => $metaValue) {

            // They should all be arrays, even if just one item
            if (!is_array($metaValue)) {
                continue;
            }

            // If the array contains one item, save it and move on
            if (count($metaValue) === 1 && isset($metaValue[0])) {
                $metas[$metaKey] = self::getSingle(
                    $metaType, $objectId, $metaKey, $metaValue[0]
                );
                continue;
            }

            // Otherwise, if the array contains multiple items with the same
            // key, then put them in an array with that key
            $metas[$metaKey] = self::getMultiple(
                $metaType, $objectId, $metaKey, $metaValue
            );
        }

        return $metas;
    }

    /**
     * Get all the metas that match the specified key.
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

        // Loop over every meta with the same key
        foreach ($metaValues as $metaValue) {
            $metas[] = self::getSingle(
                $metaType, $objectId, $metaKey, $metaValue
            );
        }

        return $metas;
    }

    /**
     * Get the single meta.
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
     * Get the meta ID field based on its type.
     *
     * @param string $metaType `post`, `user`, `term`, etc.
     * @return string
     */
    protected static function getMetaIdField(string $metaType): string
    {
        return $metaType === 'user' ? 'umeta_id' : 'meta_id';
    }
}