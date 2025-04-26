<?php

namespace Charm\Models\WordPress;

use Charm\Contracts\IsPersistable;
use Charm\Support\Result;
use stdClass;

/**
 * Represents a generic meta in WordPress.
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
     * Meta ID
     *
     * @var ?int
     */
    protected ?int $metaId = null;

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
        if (isset($data['metaId'])) {
            $this->metaId = (int) $data['metaId'];
        }

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
     * Initialize meta
     *
     * @param string $metaType
     * @param int|stdClass $key
     * @return ?static
     */
    public static function init(string $metaType, int|stdClass $key): ?static
    {
        $meta = new static($metaType);

        match (true) {
            is_numeric($key) => $meta->loadFromId((int) $key),
            is_object($key) => $meta->loadFromMetaData($key),
        };

        return !$meta->metaId ? null : $meta;
    }

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

    /**
     * Update meta with specified key and value
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
                'update_metadata_failed',
                __('update_metadata() returned false.', 'charm')
            );
        }

        return Result::success();
    }

    /**
     * Delete meta with specified key
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

        return $result ? Result::success() : Result::error();
    }

    /**
     * Whether meta with specified key exists
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
     * @see add_metadata()
     */
    public function create(): Result
    {
        if ($this->metaId !== null) {
            return Result::error(
                'meta_id_exists',
                __('Meta already exists.', 'charm')
            );
        }

        $result = add_metadata(
            $this->metaType,
            $this->objectId,
            $this->metaKey,
            $this->metaValue
        );

        if (!is_int($result)) {
            return Result::error(
                'add_metadata_failed',
                __('add_metadata() did not return an ID.', 'charm')
            );
        }

        $this->metaId = $result;
        $this->exists = true;

        return Result::success();
    }

    /**
     * Update existing meta
     *
     * @return Result
     * @see update_metadata_by_mid()
     */
    public function update(): Result
    {
        if ($this->metaId === null) {
            return Result::error(
                'meta_id_missing',
                __('Cannot update meta with blank ID.', 'charm')
            );
        }

        if ($this->metaValue === $this->prevMetaValue) {
            return Result::success();
        }

        $result = update_metadata_by_mid(
            $this->metaType,
            $this->metaId,
            $this->metaValue
        );

        if ($result !== true) {
            return Result::error(
                'update_metadata_by_mid_failed',
                __('update_metadata_by_mid() did not return true.', 'charm')
            );
        }

        $this->prevMetaValue = $this->metaValue;

        return Result::success();
    }

    /**
     * Delete meta
     *
     * @return Result
     * @see delete_metadata_by_mid()
     */
    public function delete(): Result
    {
        if ($this->metaId === null) {
            return Result::error(
                'meta_id_missing',
                __('Cannot delete meta with blank ID.', 'charm')
            );
        }

        $result = delete_metadata_by_mid($this->metaType, $this->metaId);

        if ($result !== true) {
            return Result::error(
                'delete_metadata_by_mid_failed',
                __('delete_metadata_by_mid() did not return true.', 'charm')
            );
        }

        $this->metaId = null;
        $this->exists = false;

        return Result::success();
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
     * Get meta ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->metaId ?? 0;
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
     * Load instance from ID
     *
     * @param int $id
     * @return void
     * @see get_metadata_by_mid()
     */
    protected function loadFromId(int $id): void
    {
        /**
         * @var $metaData stdClass|false {
         *     @type string $meta_key
         *     @type mixed $meta_value
         *     @type string $meta_id Set when meta type is `comment`, `post`, or `term`
         *     @type string $umeta_id Set when meta type is `user`
         *     @type string $post_id Set when meta type is `post`
         *     @type string $comment_id Set when meta type is `comment`
         *     @type string $term_id Set when meta type is `term`
         *     @type string $user_id Set when meta type is `user`
         *  }
         */
        $metaData = get_metadata_by_mid($this->metaType, $id);

        if ($metaData === false) {
            return;
        }

        $this->loadFromMetaData($metaData);
    }

    /**
     * Load instance from meta data
     *
     * @param object $metaData
     * @return void
     */
    protected function loadFromMetaData(object $metaData): void
    {
        // e.g. `meta_id` or `umeta_id`
        $metaIdField = $this->metaIdField;

        // e.g. `comment_id`, `post_id`, `user_id`, or `term_id`
        $objectIdField = $this->metaType . '_id';

        if (!property_exists($metaData, $objectIdField)) {
            return;
        }

        $this->metaId = $metaData->$metaIdField;
        $this->objectId = $metaData->$objectIdField;
        $this->metaKey = $metaData->meta_key;
        $this->metaValue = $metaData->meta_value;
    }

    // -------------------------------------------------------------------------

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
            $metas[] = self::getSingle($metaType, $objectId, $metaKey, $metaValue);
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
        global $wpdb;

        $metaTable = $metaType . 'meta';
        $metaIdField = static::getMetaIdField($metaType);

        $query = 'SELECT ' . $metaIdField . ' ';
        $query .= 'FROM ' . $wpdb->$metaTable . ' ';
        $query .= 'WHERE ' . $metaType . '_id = %d ';
        $query .= 'AND meta_key = %s ';
        $query .= 'AND meta_value = %s';

        $metaId = $wpdb->get_var(
            $wpdb->prepare($query, [$objectId, $metaKey, $metaValue])
        );

        $metaValue = maybe_unserialize($metaValue);

        return new static($metaType, [
            'metaId' => $metaId,
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