<?php

namespace Charm\Form\Field;

use Charm\Helper\Convert;

/**
 * Class Field
 *
 * @author Ryan Sechrest
 * @package Charm\Form\Field
 */
class Field
{
    /************************************************************************************/
    // Properties

    /**
     * ID
     *
     * @var string
     */
    protected string $id = '';

    /**
     * Name
     *
     * @var string
     */
    protected string $name = '';

    /**
     * Value
     *
     * @var string
     */
    protected string $value = '';

    /**
     * Classes
     *
     * @var array
     */
    protected array $classes = [];

    /**
     * Attributes
     *
     * @var array
     */
    protected array $attributes = [];

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Field constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (count($data) > 0) {
            $this->load($data);
        }
    }

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['name'])) {
            $this->name = $data['name'];
        }
        if (isset($data['value'])) {
            $this->value = $data['value'];
        }
        if (isset($data['classes'])) {
            $this->classes = $data['classes'];
        }
        if (isset($data['attributes'])) {
            $this->attributes = $data['attributes'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * New field
     *
     * @param array $params
     * @return static
     */
    public static function init(array $params): Field
    {
        return new static($params);
    }

    /**
     * Get new field as HTML
     *
     * @param array $params
     * @return string
     */
    public static function html(array $params): string
    {
        return (static::init($params))->to_html();
    }

    /**
     * Display new field as HTML
     *
     * @param array $params
     */
    public static function display(array $params): void
    {
        echo static::html($params);
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
        if ($this->id !== '') {
            $data['id'] = $this->id;
        }
        if ($this->name !== '') {
            $data['name'] = $this->name;
        }
        if ($this->value !== '') {
            $data['value'] = $this->value;
        }
        if (count($this->classes) > 0) {
            $data['classes'] = $this->classes;
        }
        if (count($this->attributes) > 0) {
            $data['attributes'] = $this->attributes;
        }

        return $data;
    }

    /**
     * Cast instance to HTML
     *
     * @return string
     */
    public function to_html(): string
    {
        $output = [];
        if ($this->id !== '') {
            $output[] = 'id="' . $this->id . '"';
        }
        if ($this->name !== '') {
            $output[] = 'name="' . $this->name . '"';
        }
        if ($this->value !== '') {
            $output[] = 'value="' . $this->value . '"';
        }
        if ($classes_html = $this->get_classes_html()) {
            $output[] = $classes_html;
        }
        if ($attributes_html = $this->get_attributes_html()) {
            $output[] = $attributes_html;
        }

        return implode(' ', $output);
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
     * Get ID
     *
     * @return string
     */
    public function get_id(): string
    {
        return $this->id;
    }

    /**
     * Get ID as HTML
     *
     * @return string
     */
    public function get_id_html(): string
    {
        return 'id="' . $this->id . '"';
    }

    /**
     * Set ID
     *
     * @param string $id
     */
    public function set_id(string $id): void
    {
        $this->id = $id;
    }

    /*----------------------------------------------------------------------------------*/

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
     * Get name as HTML
     *
     * @return string
     */
    public function get_name_html(): string
    {
        return 'name="' . $this->name . '"';
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
     * @return string
     */
    public function get_value(): string
    {
        return $this->name;
    }

    /**
     * Get value as HTML
     *
     * @return string
     */
    public function get_value_html(): string
    {
        return 'value="' . $this->value . '"';
    }

    /**
     * Set value
     *
     * @param string $value
     */
    public function set_value(string $value): void
    {
        $this->value = $value;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get classes
     *
     * @return array
     */
    public function get_classes(): array
    {
        return $this->classes;
    }

    /**
     * Get classes as HTML
     *
     * @return string
     */
    public function get_classes_html(): string
    {
        if (count($this->classes) === 0) {
            return '';
        }

        return 'class="' . Convert::init($this->classes)->a2a()->value() . '"';
    }

    /**
     * Add class
     *
     * @param string $class
     */
    public function add_class(string $class): void
    {
        $this->classes[] = $class;
    }

    /**
     * Has class?
     *
     * @param string $class
     * @return bool
     */
    public function has_class(string $class): bool
    {
        return in_array($class, $this->classes);
    }

    /**
     * Set classes
     *
     * @param array $classes
     */
    public function set_classes(array $classes): void
    {
        $this->classes = $classes;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get attributes
     *
     * @return array
     */
    public function get_attributes(): array
    {
        return $this->attributes;
    }

    /**
     * Get attributes as HTML
     *
     * @return string
     */
    public function get_attributes_html(): string
    {
        if (count($this->attributes) === 0) {
            return '';
        }

        return Convert::init($this->attributes)->a2a()->value();
    }

    /**
     * Add attribute
     *
     * @param string $key
     * @param string $value
     */
    public function add_attribute(string $key, string $value = ''): void
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Has attribute?
     *
     * @param string $attribute
     * @return bool
     */
    public function has_attribute(string $attribute): bool
    {
        return array_key_exists($attribute, $this->attributes);
    }

    /**
     * Set attributes
     *
     * @param array $attributes
     */
    public function set_attributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }
}