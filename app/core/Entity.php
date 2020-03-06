<?php

namespace Charm\App\Core;

use Charm\App\Feature\Conversion;
use Charm\App\Feature\Crud;

/**
 * Class Entity
 *
 * @author Ryan Sechrest
 * @package Charm\App\Core
 */
abstract class Entity implements Conversion, Crud
{
    /**
     * Entity constructor
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
        if (!is_array($data)) {
            return;
        }
        foreach ($data as $property => $object) {
            if (!property_exists($this, $property)) {
                continue;
            }
            $this->$property = $object;
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize object
     *
     * @param int|null|object|string $key
     * @return null|self
     */
    abstract public static function init($key = null);

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
        foreach(array_keys(get_object_vars($this)) as $property) {
            $data[$property] = null;
            if (method_exists($this->$property, 'to_array')) {
                $data[$property] = $this->$property->to_array();
            }
        }

        return $data;
    }

    /**
     * Convert instance to JSON
     *
     * @return string
     */
    public function to_json(): string
    {
        return json_encode($this->to_array());
    }
    /**
     * Convert instance to stdClass
     *
     * @return object
     */
    public function to_object(): object
    {
        return (object) $this->to_array();
    }
}