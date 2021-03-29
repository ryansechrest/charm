<?php

namespace Charm\Entity;

use Charm\Helper\Cast;
use Charm\Helper\Convert;
use Charm\WordPress\Meta as WpMeta;

/**
 * Class Meta
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class Meta extends WpMeta
{
    /************************************************************************************/
    // Constants

    /**
     * Meta type
     *
     * @var string
     */
    const META_TYPE = '';

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        $data['meta_type'] = static::META_TYPE;
        parent::load($data);
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Get meta values
     *
     * @param array $params
     * @return static[]
     */
    public static function get(array $params): array
    {
        $params['meta_type'] = static::META_TYPE;

        return parent::get($params);
    }

    /**
     * Get meta values
     *
     * @param string $meta_key
     * @param array $where
     * @return array
     */
    public static function get_values(string $meta_key, array $where = []): array
    {
        global $wpdb;

        $args = [$meta_key];
        $meta_table = static::META_TYPE . 'meta';
        $entity_table = static::META_TYPE . 's';
        $query = 'SELECT DISTINCT m.meta_value ';
        $query .= 'FROM ' . $wpdb->$meta_table . ' m ';
        $query .= 'LEFT JOIN ' . $wpdb->$entity_table . ' e on e.ID = m.' . static::META_TYPE . '_id ';
        $query .= 'WHERE m.meta_key = %s ';
        $query .= 'AND m.meta_value != "" ';
        foreach ($where as $key => $value) {
            $args[] = $value;
            $query .= 'AND e.' . $key . ' = %s ';
        }
        $query .= 'ORDER BY m.meta_value ASC';

        return $wpdb->get_col($wpdb->prepare($query, $args));
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get meta ID
     *
     * @return int
     */
    public function id(): int
    {
        return $this->meta_id;
    }

    /************************************************************************************/
    // Action methods

    /**
     * Add value to array
     *
     * @param mixed $value
     */
    public function add($value): void
    {
        if (!is_array($this->meta_value)) {
            return;
        }
        $this->meta_value[] = $value;
    }

    /************************************************************************************/
    // Helper methods

    /**
     * Pass value to cast
     *
     * @return Cast
     */
    public function cast(): Cast
    {
        return Cast::init($this->meta_value);
    }

    /**
     * Pass value to convert
     *
     * @return Convert
     */
    public function convert(): Convert
    {
        return Convert::init($this->meta_value);
    }
}