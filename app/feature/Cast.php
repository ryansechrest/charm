<?php

namespace Charm\App\Feature;

/**
 * Trait Cast
 *
 * @author Ryan Sechrest
 * @package Charm\App\Feature
 */
trait Cast
{
    /**
     * Cast instance to array
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
}