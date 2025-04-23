<?php

namespace Charm\Models\WordPress;

use Charm\Support\Result;
use stdClass;

/**
 * Represents a meta field for any model in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
abstract class BaseMeta
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
     * Since most meta types use `meta_id`, it makes for a sensible default,
     * and then allows `UserMeta` to overwrite it.
     *
     * comment -> meta_id
     * post    -> meta_id
     * term    -> meta_id
     * user    -> umeta_id
     */
    protected const META_ID_FIELD = 'meta_id';

    /*------------------------------------------------------------------------*/

    /**
     * ID
     *
     * @var ?int
     */
    protected ?int $id = null;

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

    /*------------------------------------------------------------------------*/

    /**
     * Whether meta exists in database
     *
     * @var bool
     */
    protected bool $exists = false;

    /**************************************************************************/

    /**
     * BaseMeta constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['id'])) {
            $this->id = (int) $data['id'];
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

    /**************************************************************************/

    /**
     * Initialize meta
     *
     * @param int|object $key
     * @return ?static
     */
    public static function init(int|object $key): ?static
    {
        if (static::META_TYPE === '') {
            return null;
        }

        $meta = new static();

        match (true) {
            is_numeric($key) => $meta->loadFromId((int) $key),
            is_object($key) => $meta->loadFromMetaData($key),
        };

        return !$meta->id ? null : $meta;
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
     * @see get_metadata()
     */
    public static function get(int $objectId, string $metaKey = ''): array
    {
        // Get either all metas or metas that match specified key
        $metaValues = get_metadata(
            static::META_TYPE, $objectId, $metaKey
        );

        // If objectId is invalid
        if ($metaValues ===  false) {
            return [];
        }

        // If no metas exist
        if (is_array($metaValues) && count($metaValues) === 0) {
            return [];
        }

        // If metaKey was blank, assume we need to load array of arrays
        if ($metaKey === '') {
            return self::loadAll($objectId, $metaValues);
        }

        // Assume specified meta key can occur more than once
        return self::loadMultiple($objectId, $metaKey, $metaValues);
    }

    /**
     * Update meta with specified key and value
     *
     * @param int $objectId
     * @param string $metaKey
     * @param mixed $metaValue
     * @param mixed $prevMetaValue
     * @return Result
     * @see update_metadata()
     */
    public static function updateMeta(
        int $objectId,
        string $metaKey,
        mixed $metaValue,
        mixed $prevMetaValue = null
    ): Result
    {
        $result = update_metadata(
            static::META_TYPE,
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
     * @param int $objectId
     * @param string $metaKey
     * @param mixed $metaValue
     * @return Result
     * @see delete_metadata()
     */
    public static function deleteMeta(
        int $objectId, string $metaKey, mixed $metaValue = ''
    ): Result
    {
        $result = delete_metadata(
            static::META_TYPE,
            $objectId,
            $metaKey,
            $metaValue
        );

        return $result ? Result::success() : Result::error();
    }

    /**
     * Whether meta with specified key exists
     *
     * @param int $objectId
     * @param string $metaKey
     * @return bool
     * @see metadata_exists()
     */
    public static function hasMeta(int $objectId, string $metaKey): bool
    {
        return metadata_exists(
            static::META_TYPE, $objectId, $metaKey
        );
    }

    /**************************************************************************/

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
        $metaData = get_metadata_by_mid(static::META_TYPE, $id);

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
        $idField = static::META_ID_FIELD;

        // e.g. `comment_id`, `post_id`, `user_id`, or `term_id`
        $objectIdField = static::META_TYPE . '_id';

        if (!property_exists($metaData, $objectIdField)) {
            return;
        }

        $this->id = $metaData->$idField;
        $this->objectId = $metaData->$objectIdField;
        $this->metaKey = $metaData->meta_key;
        $this->metaValue = $metaData->meta_value;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Load all metas
     *
     * @param int $objectId
     * @param array $metaValues
     * @return static[]
     */
    protected static function loadAll(int $objectId, array $metaValues): array {
        $metas = [];

        foreach ($metaValues as $metaKey => $metaValue) {

            if (!is_array($metaValue)) {
                continue;
            }

            if (count($metaValue) === 1 && isset($metaValue[0])) {
                $metas[$metaKey] = self::loadSingle(
                    $objectId, $metaKey, $metaValue[0]
                );
                continue;
            }

            $metas[$metaKey] = self::loadMultiple(
                $objectId, $metaKey, $metaValue
            );
        }

        return $metas;
    }

    /**
     * Load meta with multiple, identical keys
     *
     * @param int $objectId
     * @param string $metaKey
     * @param array $metaValues
     * @return static[]
     */
    protected static function loadMultiple(
        int $objectId, string $metaKey, array $metaValues
    ): array {
        $metas = [];

        foreach ($metaValues as $metaValue) {
            $metas[] = self::loadSingle($objectId, $metaKey, $metaValue);
        }

        return $metas;
    }

    /**
     * Load meta with single key and value
     *
     * @param int $objectId
     * @param string $metaKey
     * @param mixed $metaValue
     * @return static
     * @see maybe_unserialize()
     */
    protected static function loadSingle(
        int $objectId, string $metaKey, mixed $metaValue
    ): static {
        global $wpdb;

        $metaTable = static::META_TYPE . 'meta';

        $query = 'SELECT ' . static::META_ID_FIELD . ' ';
        $query .= 'FROM ' . $wpdb->$metaTable . ' ';
        $query .= 'WHERE ' . static::META_TYPE . '_id = %d ';
        $query .= 'AND meta_key = %s ';
        $query .= 'AND meta_value = %s';

        $metaId = $wpdb->get_var(
            $wpdb->prepare($query, [$objectId, $metaKey, $metaValue])
        );

        $metaValue = maybe_unserialize($metaValue);

        return new static([
            'id' => $metaId,
            'objectId' => $objectId,
            'metaKey' => $metaKey,
            'metaValue' => $metaValue,
            'prevMetaValue' => $metaValue,
            'exists' => true,
        ]);
    }

    /**************************************************************************/

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
        if ($this->id !== null) {
            return Result::error(
                'existing_meta_id',
                __('Meta already exists.', 'charm')
            );
        }

        $result = add_metadata(
            static::META_TYPE,
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

        $this->id = $result;
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
        if ($this->id === null) {
            return Result::error(
                'missing_meta_id',
                __('Cannot update meta with blank ID.', 'charm')
            );
        }

        if ($this->metaValue === $this->prevMetaValue) {
            return Result::error(
                'meta_value_unchanged',
                __('Meta has not changed', 'charm')
            );
        }

        $result = update_metadata_by_mid(
            static::META_TYPE,
            $this->id,
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
        if ($this->id === null) {
            return Result::error(
                'missing_meta_id',
                __('Cannot delete meta with blank ID.', 'charm')
            );
        }

        $result = delete_metadata_by_mid(
            static::META_TYPE,
            $this->id
        );

        if ($result !== true) {
            return Result::error(
                'delete_metadata_by_mid_failed',
                __('delete_metadata_by_mid() did not return true.', 'charm')
            );
        }

        $this->id = null;
        $this->exists = false;

        return Result::success();
    }

    /**************************************************************************/

    /**
     * Get ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id ?? 0;
    }

    /*------------------------------------------------------------------------*/

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

    /*------------------------------------------------------------------------*/

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

    /*------------------------------------------------------------------------*/

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

    /*------------------------------------------------------------------------*/

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
}