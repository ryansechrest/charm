<?php

namespace Charm\Module\Rest\Param;

/**
 * Class ArrayParam
 *
 * @author Ryan Sechrest
 * @package Charm\Module\Rest\Param
 */
class ArrayParam extends Param
{
    /************************************************************************************/
    // Properties

    /**
     * Items
     *
     * @var array
     */
    protected $items = [];

    /**
     * Unique items
     *
     * @var array
     */
    protected $unique_items = [];

    /**
     * Min items
     *
     * @var int
     */
    protected $min_items = 0;

    /**
     * Max items
     *
     * @var int
     */
    protected $max_items = 0;

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
        $this->type = 'array';
        if (isset($data['items'])) {
            $this->items = $data['items'];
        }
        if (isset($data['unique_items'])) {
            $this->unique_items = $data['unique_items'];
        }
        if (isset($data['min_items'])) {
            $this->min_items = $data['min_items'];
        }
        if (isset($data['max_items'])) {
            $this->max_items = $data['max_items'];
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
        if (count($this->items) > 0) {
            $data['items'] = $this->items;
        }
        if (count($this->unique_items) > 0) {
            $data['unique_items'] = $this->unique_items;
        }
        if ($this->min_items !== 0) {
            $data['min_items'] = $this->min_items;
        }
        if ($this->max_items !== 0) {
            $data['max_items'] = $this->max_items;
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
        if (count($this->items) > 0) {
            $data['items'] = $this->items;
        }
        if (count($this->unique_items) > 0) {
            $data['uniqueItems'] = $this->unique_items;
        }
        if ($this->min_items !== 0) {
            $data['minItems'] = $this->min_items;
        }
        if ($this->max_items !== 0) {
            $data['maxItems'] = $this->max_items;
        }

        return $data;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get items
     *
     * @return array
     */
    public function get_items(): array
    {
        return $this->items;
    }

    /**
     * Set items
     *
     * @param array $items
     */
    public function set_items(array $items): void
    {
        $this->items = $items;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get unique items
     *
     * @return array
     */
    public function get_unique_items(): array
    {
        return $this->unique_items;
    }

    /**
     * Set unique items
     *
     * @param array $unique_items
     */
    public function set_unique_items(array $unique_items): void
    {
        $this->unique_items = $unique_items;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get min items
     *
     * @return int
     */
    public function get_min_items(): int
    {
        return $this->min_items;
    }

    /**
     * Set min items
     *
     * @param int $min_items
     */
    public function set_min_items(int $min_items): void
    {
        $this->min_items = $min_items;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get max items
     *
     * @return int
     */
    public function get_max_items(): int
    {
        return $this->max_items;
    }

    /**
     * Set max items
     *
     * @param int $max_items
     */
    public function set_max_items(int $max_items): void
    {
        $this->max_items = $max_items;
    }
}