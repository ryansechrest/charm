<?php

namespace Charm\WordPress\Core;

/**
 * Class Meta
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Core
 */
class Meta
{
    /**
     * Meta type
     *
     * @var string
     */
    private $meta_type;

    /**
     * Meta ID
     *
     * @var int
     */
    private $meta_id;

    /**
     * Object ID
     *
     * @var int
     */
    private $object_id;

    /**
     * Meta key
     *
     * @var string
     */
    private $meta_key;

    /**
     * Meta value
     *
     * @var mixed
     */
    private $meta_value;

    /**
     * Previous meta value
     *
     * @var mixed
     */
    private $prev_value;

    /**
     * Loaded from database?
     *
     * @var bool
     */
    private $from_db = false;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Default constructor
     *
     * @param array $data
     */
    public function __construct($data = [])
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
    public function load(array $data)
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
            $this->meta_value = $this->prev_value = $data['meta_value'];
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
     * Get meta data
     *
     * @see get_metadata()
     * @param string $meta_type
     * @param int $object_id
     * @param string $meta_key
     * @return array|Meta|Meta[]|null
     */
    public static function get($meta_type, $object_id, $meta_key = '')
    {
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
    private static function load_all($meta_type, $object_id, $meta_values)
    {
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
    private static function load_multi($meta_type, $object_id, $meta_key, $meta_values)
    {
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
    private static function load_single($meta_type, $object_id, $meta_key, $meta_value)
    {
        $data = [
            'meta_type' => $meta_type,
            'object_id' => $object_id,
            'meta_key' => $meta_key,
            'meta_value' => $meta_value,
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
    public function save()
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
    public function create()
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
    public function update()
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
    public function delete()
    {
        if (!delete_metadata(
            $this->meta_type, $this->object_id, $this->meta_key, $this->meta_value
        )) {
            return false;
        }

        return true;
    }

    /************************************************************************************/
    // Cast methods

    /**
     * Return value as array
     *
     * @see maybe_unserialize()
     * @return array
     */
    public function array()
    {
        $array = maybe_unserialize($this->meta_value);
        if (!is_array($array)) {
            return [];
        }

        return $array;
    }

    /**
     * Return value as integer
     *
     * @return string
     */
    public function integer()
    {
        if (!ctype_digit($this->meta_value)) {
            return 0;
        }

        return (int) $this->meta_value;
    }

    /**
     * Return value as text
     *
     * @return string
     */
    public function text()
    {
        if (!$string = (string) $this->meta_value) {
            return '';
        }

        return $string;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get meta type
     *
     * @return string
     */
    public function get_meta_type()
    {
        return $this->meta_type;
    }

    /**
     * Set meta type
     *
     * @param string $meta_type
     */
    public function set_meta_type($meta_type)
    {
        $this->meta_type = $meta_type;
    }

    /**
     * Get meta ID
     *
     * @return int
     */
    public function get_meta_id()
    {
        return $this->meta_id;
    }

    /**
     * Set meta ID
     *
     * @param int $meta_id
     */
    public function set_meta_id($meta_id)
    {
        $this->meta_id = $meta_id;
    }

    /**
     * Get object ID
     *
     * @return int
     */
    public function get_object_id()
    {
        return $this->object_id;
    }

    /**
     * Set object ID
     *
     * @param int $object_id
     */
    public function set_object_id($object_id)
    {
        $this->object_id = $object_id;
    }

    /**
     * Get meta key
     *
     * @return string
     */
    public function get_meta_key()
    {
        return $this->meta_key;
    }

    /**
     * Set meta ID
     *
     * @param string $meta_key
     */
    public function set_meta_key($meta_key)
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
    public function set_meta_value($meta_value)
    {
        $this->meta_value = $meta_value;
    }

    /**
     * Get previous meta value
     *
     * @return mixed
     */
    public function get_prev_value()
    {
        return $this->prev_value;
    }

    /**
     * Set previous meta value
     *
     * @param mixed $prev_value
     */
    public function set_prev_value($prev_value)
    {
        $this->prev_value = $prev_value;
    }

    /**
     * Is from database
     *
     * @return bool
     */
    public function is_from_db()
    {
        return $this->from_db;
    }

    /**
     * Set is from database
     *
     * @param bool $from_db
     */
    public function set_from_db($from_db)
    {
        $this->from_db = $from_db;
    }
}