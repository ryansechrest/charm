<?php

namespace Charm\Module\Rest;

/**
 * Class ObjectParam
 *
 * @author Ryan Sechrest
 * @package Charm\Module\Rest
 */
class ObjectParam extends Param
{
    /************************************************************************************/
    // Properties

    /**
     * Properties
     *
     * @var array
     */
    protected $properties = [];

    /**
     * Additional properties
     *
     * @var array
     */
    protected $additional_properties = [];

    /**
     * Pattern properties
     *
     * @var array
     */
    protected $pattern_properties = [];

    /**
     * Min properties
     *
     * @var int
     */
    protected $min_properties = 0;

    /**
     * Max properties
     *
     * @var int
     */
    protected $max_properties = 0;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        parent::load($data);
        $this->type = 'object';
        if (isset($data['properties'])) {
            $this->properties = $data['properties'];
        }
        if (isset($data['additional_properties'])) {
            $this->additional_properties = $data['additional_properties'];
        }
        if (isset($data['pattern_properties'])) {
            $this->pattern_properties = $data['pattern_properties'];
        }
        if (isset($data['min_properties'])) {
            $this->min_properties = $data['min_properties'];
        }
        if (isset($data['max_properties'])) {
            $this->max_properties = $data['max_properties'];
        }
    }

    /************************************************************************************/
    // Cast methods

    /**
     * Cast properties to array
     *
     * @return array
     */
    public function to_array(): array
    {
        $data = parent::to_array();
        if (count($this->properties) > 0) {
            $data['properties'] = $this->properties;
        }
        if (count($this->additional_properties) > 0) {
            $data['additional_properties'] = $this->additional_properties;
        }
        if (count($this->pattern_properties) > 0) {
            $data['pattern_properties'] = $this->pattern_properties;
        }
        if ($this->min_properties !== 0) {
            $data['min_properties'] = $this->min_properties;
        }
        if ($this->max_properties !== 0) {
            $data['max_properties'] = $this->max_properties;
        }

        return $data;
    }

    /**
     * Cast properties to array for WordPress
     *
     * @return array
     */
    public function to_array_for_wp(): array
    {
        $data = parent::to_array();
        if (count($this->properties) > 0) {
            $data['properties'] = $this->properties;
        }
        if (count($this->additional_properties) > 0) {
            $data['additionalProperties'] = $this->additional_properties;
        }
        if (count($this->pattern_properties) > 0) {
            $data['patternProperties'] = $this->pattern_properties;
        }
        if ($this->min_properties !== 0) {
            $data['minProperties'] = $this->min_properties;
        }
        if ($this->max_properties !== 0) {
            $data['maxProperties'] = $this->max_properties;
        }

        return $data;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get properties
     *
     * @return array
     */
    public function get_properties(): array
    {
        return $this->properties;
    }

    /**
     * Set properties
     *
     * @param array $properties
     */
    public function set_properties(array $properties): void
    {
        $this->properties = $properties;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get additional properties
     *
     * @return array
     */
    public function get_additional_properties(): array
    {
        return $this->additional_properties;
    }

    /**
     * Set additional properties
     *
     * @param array $additional_properties
     */
    public function set_additional_properties(array $additional_properties): void
    {
        $this->additional_properties = $additional_properties;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get pattern properties
     *
     * @return array
     */
    public function get_pattern_properties(): array
    {
        return $this->pattern_properties;
    }

    /**
     * Set pattern properties
     *
     * @param array $pattern_properties
     */
    public function set_pattern_properties(array $pattern_properties): void
    {
        $this->pattern_properties = $pattern_properties;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get min properties
     *
     * @return int
     */
    public function get_min_properties(): int
    {
        return $this->min_properties;
    }

    /**
     * Set min properties
     *
     * @param int $min_properties
     */
    public function set_min_properties(int $min_properties): void
    {
        $this->min_properties = $min_properties;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get max properties
     *
     * @return int
     */
    public function get_max_properties(): int
    {
        return $this->max_properties;
    }

    /**
     * Set max properties
     *
     * @param int $max_properties
     */
    public function set_max_properties(int $max_properties): void
    {
        $this->max_properties = $max_properties;
    }
}