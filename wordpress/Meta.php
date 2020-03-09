<?php

namespace Charm\WordPress;

use Charm\App\Blueprint\Cast;
use Charm\App\Blueprint\Entity;
use Charm\App\Feature\Cast as CastFeature;

/**
 * Class Meta
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress
 */
class Meta implements Cast, Entity
{
    use CastFeature;

    /**
     * Meta type
     *
     * @var string
     */
    private $meta_type = '';

    /**
     * Meta ID
     *
     * @var int
     */
    private $meta_id = 0;

    /**
     * Object ID
     *
     * @var int
     */
    private $object_id = 0;

    /**
     * Meta key
     *
     * @var string
     */
    private $meta_key = '';

    /**
     * Meta value
     *
     * @var mixed
     */
    private $meta_value = null;

    /**
     * Previous meta value
     *
     * @var mixed
     */
    private $prev_value = null;

    /**
     * Loaded from database?
     *
     * @var bool
     */
    private $from_db = false;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Meta constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (!is_array($data)) {
            return;
        }
        $this->load($data);
    }

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['meta_type'])) {
            $this->meta_type = $data['meta_type'];
        }
        if (isset($data['meta_id'])) {
            $this->meta_id = $data['meta_id'];
        }
        if (isset($data['object_id'])) {
            $this->object_id = $data['object_id'];
        }
        if (isset($data['meta_key'])) {
            $this->meta_key = $data['meta_key'];
        }
        if (isset($data['meta_value'])) {
            $this->meta_value = $data['meta_value'];
        }
        if (isset($data['prev_value'])) {
            $this->prev_value = $data['prev_value'];
        }
        if (isset($data['from_db'])) {
            $this->from_db = $data['from_db'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize meta(s)
     *
     * @see get_metadata()
     * @param array $params
     * @return array|Meta|Meta[]|null
     */
    public static function init($params)
    {
        if (!isset($params['meta_type']) || !isset($params['object_id'])) {
            return null;
        }
        $meta_type = $params['meta_type'];
        $object_id = $params['object_id'];
        $meta_key = '';
        if (isset($params['meta_key'])) {
            $meta_key = $params['meta_key'];
        }
        $meta_values = get_metadata($meta_type, $object_id, $meta_key);
        if (!is_array($meta_values) || count($meta_values) === 0) {
            return null;
        }
        if ($meta_key === '') {
            return self::load_all(
                $meta_type, $object_id, $meta_values
            );
        }
        if (count($meta_values) > 1) {
            return self::load_multi(
                $meta_type, $object_id, $meta_key, $meta_values
            );
        }

        return self::load_single(
            $meta_type, $object_id, $meta_key, $meta_values[0]
        );
    }

    /**
     * Get metas
     *
     * @todo Implement Meta::get()
     * @param array $params
     * @return Meta[]
     */
    public static function get(array $params): array
    {
        return [];
    }

    /************************************************************************************/
    // Private load methods

    /**
     * Load all meta
     *
     * @param string $meta_type
     * @param int $object_id
     * @param array $meta_values
     * @return array
     */
    private static function load_all(
        string $meta_type, int $object_id, array $meta_values
    ): array {
        $all = [];
        foreach ($meta_values as $meta_key => $meta_value) {
            if (!is_array($meta_values)) {
                continue;
            }
            if (count($meta_value) === 1 && isset($meta_value[0])) {
                $all[$meta_key] = self::load_single(
                    $meta_type, $object_id, $meta_key, $meta_value[0]
                );
                continue;
            }
            $all[$meta_key] = self::load_multi(
                $meta_type, $object_id, $meta_key, $meta_value
            );
        }

        return $all;
    }

    /**
     * Load meta with multiple, identical keys
     *
     * @param string $meta_type
     * @param int $object_id
     * @param string $meta_key
     * @param array $meta_values
     * @return Meta[]
     */
    private static function load_multi(
        string $meta_type, int $object_id, string $meta_key, array $meta_values
    ): array {
        $multi = [];
        foreach ($meta_values as $meta_value) {
            $multi[] = self::load_single(
                $meta_type, $object_id, $meta_key, $meta_value
            );
        }

        return $multi;
    }

    /**
     * Load meta with single key and value
     *
     * @param string $meta_type
     * @param int $object_id
     * @param string $meta_key
     * @param mixed $meta_value
     * @return Meta
     */
    private static function load_single(
        string $meta_type, int $object_id, string $meta_key, $meta_value
    ): Meta {
        $data = [
            'meta_type' => $meta_type,
            'object_id' => $object_id,
            'meta_key' => $meta_key,
            'meta_value' => $meta_value,
            'prev_value' => $meta_value,
            'from_db' => true,
        ];
        $child = get_called_class();

        return new $child($data);
    }

    /************************************************************************************/
    // Action methods

    /**
     * Save meta
     *
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->from_db) {
            return $this->create();
        }

        return $this->update();
    }

    /**
     * Create new meta
     *
     * @see add_metadata()
     * @return bool
     */
    public function create(): bool
    {
        if (!$meta_id = add_metadata(
            $this->meta_type, $this->object_id, $this->meta_key, $this->meta_value
        )) {
            return false;
        }
        $this->meta_id = $meta_id;
        $this->from_db = true;

        return true;
    }

    /**
     * Update existing meta
     *
     * @see update_metadata()
     * @return bool
     */
    public function update(): bool
    {
        if (!update_metadata(
            $this->meta_type,
            $this->object_id,
            $this->meta_key,
            $this->meta_value,
            $this->prev_value
        )) {
            return false;
        }
        $this->prev_value = $this->meta_value;

        return true;
    }

    /**
     * Delete meta
     *
     * @see delete_metadata()
     * @return bool
     */
    public function delete(): bool
    {
        if (!delete_metadata(
            $this->meta_type, $this->object_id, $this->meta_key, $this->meta_value
        )) {
            return false;
        }

        return true;
    }

    /************************************************************************************/
    // Conversion methods

    /**
     * Convert instance to array
     *
     * @return array
     */
    public function to_array(): array
    {
        $data = [];
        $data['meta_type'] = $this->meta_type;
        $data['meta_id'] = $this->meta_id;
        $data['object_id'] = $this->object_id;
        $data['meta_key'] = $this->meta_key;
        $data['meta_value'] = $this->meta_value;

        return $data;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get meta type
     *
     * @return string
     */
    public function get_meta_type(): string
    {
        return $this->meta_type;
    }

    /**
     * Set meta type
     *
     * @param string $meta_type
     */
    public function set_meta_type(string $meta_type): void
    {
        $this->meta_type = $meta_type;
    }

    /**
     * Get meta ID
     *
     * @return int
     */
    public function get_meta_id(): int
    {
        return $this->meta_id;
    }

    /**
     * Set meta ID
     *
     * @param int $meta_id
     */
    public function set_meta_id(int $meta_id): void
    {
        $this->meta_id = $meta_id;
    }

    /**
     * Get object ID
     *
     * @return int
     */
    public function get_object_id(): int
    {
        return $this->object_id;
    }

    /**
     * Set object ID
     *
     * @param int $object_id
     */
    public function set_object_id(int $object_id): void
    {
        $this->object_id = $object_id;
    }

    /**
     * Get meta key
     *
     * @return string
     */
    public function get_meta_key(): string
    {
        return $this->meta_key;
    }

    /**
     * Set meta key
     *
     * @param string $meta_key
     */
    public function set_meta_key(string $meta_key): void
    {
        $this->meta_key = $meta_key;
    }

    /**
     * Get meta value
     *
     * @return mixed
     */
    public function get_meta_value()
    {
        return $this->meta_value;
    }

    /**
     * Set meta value
     *
     * @param mixed $meta_value
     */
    public function set_meta_value($meta_value): void
    {
        $this->meta_value = $meta_value;
    }

    /**
     * Get previous value
     *
     * @return mixed
     */
    public function get_prev_value()
    {
        return $this->prev_value;
    }

    /**
     * Set previous value
     *
     * @param mixed $prev_value
     */
    public function set_prev_value($prev_value): void
    {
        $this->prev_value = $prev_value;
    }

    /**
     * Is value from database?
     *
     * @return bool
     */
    public function is_from_db(): bool
    {
        return $this->from_db;
    }

    /**
     * Set whether value is from database
     *
     * @param bool $from_db
     */
    public function set_from_db(bool $from_db): void
    {
        $this->from_db = $from_db;
    }

    /**
     * Has value changed?
     *
     * @return bool
     */
    public function has_changed()
    {
        if ($this->meta_value !== $this->prev_value) {
            return true;
        }

        return false;
    }
}