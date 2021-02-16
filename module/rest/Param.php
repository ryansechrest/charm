<?php

namespace Charm\Module\Rest;

/**
 * Class Param
 *
 * @author Ryan Sechrest
 * @package Charm\Module\Rest
 */
class Param
{
    /************************************************************************************/
    // Properties

    /**
     * Name
     *
     * e.g. 'foo'
     *
     * @var string
     */
    protected $name = '';

    /**
     * Title
     *
     * e.g. 'Foo'
     *
     * @var string
     */
    protected $title = '';

    /**
     * Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * Type
     *
     * Options: 'array', 'object', 'string', 'number', 'integer', 'boolean', 'null'
     *
     * @var string
     */
    protected $type = '';

    /**
     * Default
     *
     * Used as the default value for the argument, if none is supplied.
     *
     * @var string
     */
    protected $default = '';

    /**
     * Required
     *
     * If defined as true, and no value is passed for that argument, an error will be
     * returned. No effect if a default value is set, as the argument will always have
     * a value.
     *
     * @var bool
     */
    protected $required = false;

    /**
     * Validate callback
     *
     * Used to pass a function that will be passed the value of the argument.
     * That function should return true if the value is valid, and false if not.
     *
     * @var callable
     */
    protected $validate_callback = null;

    /**
     * Sanitize callback
     *
     * Used to pass a function that is used to sanitize the value of the argument before
     * passing it to the main callback.
     *
     * @var callable
     */
    protected $sanitize_callback = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Param constructor
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
        if (isset($data['title'])) {
            $this->title = $data['title'];
        }
        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
        if (isset($data['type'])) {
            $this->type = $data['type'];
        }
        if (isset($data['default'])) {
            $this->default = $data['default'];
        }
        if (isset($data['required'])) {
            $this->required = $data['required'];
        }
        if (isset($data['validate_callback'])) {
            $this->validate_callback = $data['validate_callback'];
        }
        if (isset($data['sanitize_callback'])) {
            $this->sanitize_callback = $data['sanitize_callback'];
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
        $data = [];
        if ($this->name !== '') {
            $data['name'] = $this->name;
        }
        if ($this->title !== '') {
            $data['title'] = $this->title;
        }
        if ($this->description !== '') {
            $data['description'] = $this->description;
        }
        if ($this->type !== '') {
            $data['type'] = $this->type;
        }
        if ($this->default !== '') {
            $data['default'] = $this->default;
        }
        if (is_bool($this->required)) {
            $data['required'] = $this->required;
        }
        if ($this->validate_callback !== null) {
            $data['validate_callback'] = $this->validate_callback;
        }
        if ($this->sanitize_callback !== null) {
            $data['sanitize_callback'] = $this->sanitize_callback;
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
     * Get title
     *
     * @return string
     */
    public function get_title(): string
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function set_title(string $title): void
    {
        $this->title = $title;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get description
     *
     * @return string
     */
    public function get_description(): string
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function set_description(string $description): void
    {
        $this->description = $description;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get type
     *
     * @return string
     */
    public function get_type(): string
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function set_type(string $type): void
    {
        $this->type = $type;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get default
     *
     * @return string
     */
    public function get_default(): string
    {
        return $this->default;
    }

    /**
     * Set default
     *
     * @param string $default
     */
    public function set_default(string $default): void
    {
        $this->default = $default;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is required?
     *
     * @return bool
     */
    public function is_required(): bool
    {
        return $this->required;
    }

    /**
     * Set required
     *
     * @param bool $required
     */
    public function set_required(bool $required): void
    {
        $this->required = $required;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get validate callback
     *
     * @return callable
     */
    public function get_validate_callback(): ?callable
    {
        return $this->validate_callback;
    }

    /**
     * Set validate callback
     *
     * @param callable $validate_callback
     */
    public function set_validate_callback(callable $validate_callback): void
    {
        $this->validate_callback = $validate_callback;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get sanitize callback
     *
     * @return callable
     */
    public function get_sanitize_callback(): callable
    {
        return $this->sanitize_callback;
    }

    /**
     * Set sanitize callback
     *
     * @param callable $sanitize_callback
     */
    public function set_sanitize_callback(callable $sanitize_callback): void
    {
        $this->sanitize_callback = $sanitize_callback;
    }
}