<?php

namespace Charm\WordPress;

/**
 * Class Meta
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress
 */
class Meta
{
    /************************************************************************************/
    // Properties

    /**
     * Meta type
     *
     * @var string
     */
    protected $meta_type = '';

    /**
     * Meta ID
     *
     * @var int
     */
    protected $meta_id = 0;

    /**
     * Object ID
     *
     * @var int
     */
    protected $object_id = 0;

    /**
     * Meta key
     *
     * @var string
     */
    protected $meta_key = '';

    /**
     * Meta value
     *
     * @var mixed
     */
    protected $meta_value = null;

    /*----------------------------------------------------------------------------------*/

    /**
     * Previous meta value
     *
     * @var mixed
     */
    protected $prev_value = null;

    /**
     * Loaded from database?
     *
     * @var bool
     */
    protected $from_db = false;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Meta constructor
     *
     * Not doing an if (count($data)) check here like in other load methods so that
     * when a child class is instantiated, it will always call the overridden
     * load method of that child class, which sets the meta type. The meta type is
     * needed to get data from the correct table.
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
     * Initialize meta
     *
     * @param int $id
     * @return static|null
     */
    public static function init(int $id): ?Meta
    {
        $meta = new static();
        $meta->load_from_id($id);
        if ($meta->get_meta_key() === '') {
            return null;
        }

        return $meta;
    }

    /**
     * Get single meta
     *
     * @param array $params
     * @return static|null
     */
    public static function single(array $params): ?Meta
    {
        $metas = static::get($params);
        if (count($metas) === 0) {
            return null;
        }

        return $metas[0];
    }

    /**
     * Get metas
     *
     * @see get_metadata()
     * @param array $params
     * @return static[]
     */
    public static function get(array $params): array
    {
        // If there is no meta_type or object_id, we don't know where or what to save
        if (!isset($params['meta_type']) || !isset($params['object_id'])) {
            return [];
        }

        // Save meta_type and object_id to vars for easier reading
        $meta_type = $params['meta_type'];
        $object_id = $params['object_id'];

        // Set meta_key to blank, in case all metas are requested
        $meta_key = '';

        // If meta_key is provided, only get meta(s) that match key
        if (isset($params['meta_key'])) {
            $meta_key = $params['meta_key'];
        }

        // Get all metas or only metas that match provided key
        $meta_values = get_metadata($meta_type, $object_id, $meta_key);

        // If no array returned (which is always true), there is nothing to return
        if (!is_array($meta_values) || count($meta_values) === 0) {
            return [];
        }

        // If meta_key was blank, assume we need to load an array of arrays
        if ($meta_key === '') {
            return self::load_all(
                $meta_type, $object_id, $meta_values
            );
        }

        // Assume that more than one value could be saved for that key
        return self::load_multi(
            $meta_type, $object_id, $meta_key, $meta_values
        );
    }

    /************************************************************************************/
    // Protected load methods

    /**
     * Load instance from ID
     *
     * @see get_metadata_by_mid()
     * @param int $id
     */
    protected function load_from_id(int $id)
    {
        if (!$object = get_metadata_by_mid($this->meta_type, $id)) {
            return;
        }
        $this->set_meta_id($id);
        $object_id = $this->get_meta_type() . '_id';
        if (property_exists($object, $object_id)) {
            $this->set_object_id($object->$object_id);
        }
        $this->set_meta_key($object->meta_key);
        $this->set_prev_value($object->meta_value);
        $this->set_meta_value($object->meta_value);
        $this->set_from_db(true);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Load all meta
     *
     * @param string $meta_type
     * @param int $object_id
     * @param array $meta_values
     * @return array
     */
    protected static function load_all(
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
    protected static function load_multi(
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
     * @see maybe_unserialize()
     * @param string $meta_type
     * @param int $object_id
     * @param string $meta_key
     * @param mixed $meta_value
     * @return Meta
     */
    protected static function load_single(
        string $meta_type, int $object_id, string $meta_key, $meta_value
    ): Meta {
        global $wpdb;
        $id_col = $meta_type !== 'user' ? 'meta_id' : 'umeta_id';
        $meta_table = $meta_type . 'meta';
        $query = 'SELECT ' . $id_col . ' ';
        $query .= 'FROM ' . $wpdb->$meta_table . ' ';
        $query .= 'WHERE ' . $meta_type . '_id = %d AND meta_key = %s AND meta_value = %s';
        $meta_id = $wpdb->get_var(
            $wpdb->prepare($query, [$object_id, $meta_key, $meta_value])
        );
        return new static([
            'meta_type' => $meta_type,
            'meta_id' => $meta_id,
            'object_id' => $object_id,
            'meta_key' => $meta_key,
            'meta_value' => maybe_unserialize($meta_value),
            'prev_value' => maybe_unserialize($meta_value),
            'from_db' => true,
        ]);
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
        $meta_id = add_metadata(
            $this->meta_type,
            $this->object_id,
            $this->meta_key,
            $this->meta_value
        );
        if (!$meta_id) {
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
        if ($this->prev_value === $this->meta_value) {
            return false;
        }
        $success = update_metadata(
            $this->meta_type,
            $this->object_id,
            $this->meta_key,
            $this->meta_value,
            $this->prev_value
        );
        if (!$success) {
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
        $success = delete_metadata(
            $this->meta_type,
            $this->object_id,
            $this->meta_key,
            $this->meta_value
        );
        if (!$success) {
            return false;
        }
        $this->from_db = false;

        return true;
    }

    /************************************************************************************/
    // Cast methods

    /**
     * Cast instance to array
     *
     * @return array
     */
    public function to_array(): array
    {
        $data = [];
        if ($this->meta_type !== '') {
            $data['meta_type'] = $this->meta_type;
        }
        if ($this->meta_id !== 0) {
            $data['meta_id'] = $this->meta_id;
        }
        if ($this->object_id !== 0) {
            $data['object_id'] = $this->object_id;
        }
        if ($this->meta_key !== '') {
            $data['meta_key'] = $this->meta_key;
        }
        if ($this->prev_value !== null) {
            $data['prev_value'] = $this->prev_value;
        }
        if ($this->meta_value !== null) {
            $data['meta_value'] = $this->meta_value;
        }

        return $data;
    }

    /**
     * Cast instance to JSON
     *
     * @return string
     */
    public function to_json(): string
    {
        return json_encode($this->to_array());
    }
    /**
     * Cast instance to object
     *
     * @return object
     */
    public function to_object(): object
    {
        return (object) $this->to_array();
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

    /*----------------------------------------------------------------------------------*/

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

    /*----------------------------------------------------------------------------------*/

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

    /*----------------------------------------------------------------------------------*/

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

    /*----------------------------------------------------------------------------------*/

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

    /*----------------------------------------------------------------------------------*/

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

    /*----------------------------------------------------------------------------------*/

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

    /*----------------------------------------------------------------------------------*/

    /**
     * Has value changed?
     *
     * @return bool
     */
    public function changed(): bool
    {
        if ($this->meta_value !== $this->prev_value) {
            return true;
        }

        return false;
    }
}