<?php

namespace Charm\WordPress;

/**
 * Class Option
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress
 */
class Option
{
    /************************************************************************************/
    // Properties

    /**
     * Name
     *
     * @var string
     */
    protected $name = '';

    /**
     * Value
     *
     * @var mixed
     */
    protected $value = null;

    /*----------------------------------------------------------------------------------*/

    /**
     * Previous value
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
     * Option constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (count($data) === 0) {
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
        if (isset($data['name'])) {
            $this->name = $data['name'];
        }
        if (isset($data['value'])) {
            $this->value = $data['value'];
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
     * Initialize option
     *
     * @see get_option()
     * @param string $name
     * @return static
     */
    public static function init(string $name): Option
    {
        $option = new static([
            'name' => $name,
        ]);
        $value = get_option($name);
        if ($value !== false) {
            $option->load([
                'value' => $value,
                'prev_value' => $value,
                'from_db' => true,
            ]);
        }

        return $option;
    }

    /************************************************************************************/
    // Action methods

    /**
     * Save option
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
     * Create option
     *
     * @see add_option()
     * @return bool
     */
    public function create(): bool
    {
        $success = add_option($this->name, $this->value);
        if ($success !== true) {
            return false;
        }
        $this->from_db = true;

        return true;
    }

    /**
     * Update option
     *
     * @see update_option()
     * @return bool
     */
    public function update(): bool
    {
        if ($this->prev_value === $this->value) {
            return false;
        }
        $success = update_option($this->name, $this->value);
        if ($success !== true) {
            return false;
        }
        $this->prev_value = $this->value;

        return true;
    }

    /**
     * Delete option
     *
     * @see delete_option()
     * @return bool
     */
    public function delete(): bool
    {
        $success = delete_option($this->name);
        if ($success !== true) {
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
        if ($this->name !== '') {
            $data['name'] = $this->name;
        }
        if ($this->value !== null) {
            $data['value'] = $this->value;
        }
        if ($this->prev_value !== null) {
            $data['prev_value'] = $this->prev_value;
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
     * Get name
     *
     * @return string
     */
    public function get_name(): string
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function set_name(string $name): void
    {
        $this->name = $name;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get value
     *
     * @return mixed
     */
    public function get_value()
    {
        return $this->value;
    }

    /**
     * Set value
     *
     * @param mixed $value
     */
    public function set_value($value): void
    {
        $this->value = $value;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get prev value
     *
     * @return mixed
     */
    public function get_prev_value()
    {
        return $this->prev_value;
    }

    /**
     * Set prev value
     *
     * @param mixed $prev_value
     */
    public function set_prev_value($prev_value): void
    {
        $this->prev_value = $prev_value;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is from DB?
     *
     * @return bool
     */
    public function is_from_db(): bool
    {
        return $this->from_db;
    }

    /**
     * Set from DB
     *
     * @param bool $from_db
     */
    public function set_from_db(bool $from_db): void
    {
        $this->from_db = $from_db;
    }
}