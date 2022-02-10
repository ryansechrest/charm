<?php

namespace Charm\FormElement;

use Charm\Helper\Convert;
use Charm\Helper\Generate;

/**
 * Class Field
 *
 * @author Ryan Sechrest
 * @package Charm\FormElement
 */
class Field
{
    /************************************************************************************/
    // Properties

    /**
     * Label
     *
     * @var string
     */
    protected string $label = '';

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
     * Database value (single)
     *
     * @var string
     */
    protected string $db_value = '';

    /**
     * Database values (multiple)
     *
     * @var array
     */
    protected array $db_values = [];

    /**
     * Multiple
     *
     * @var ?bool
     */
    protected ?bool $multiple = null;

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

    /*----------------------------------------------------------------------------------*/

    /**
     * Label object
     *
     * @var Label|null
     */
    protected ?Label $label_obj = null;

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
        if (isset($data['label'])) {
            $this->label = $data['label'];
        }
        if (isset($data['id'])) {
            $this->id = $data['id'];
        } elseif ($this->id === '') {
            $this->id = $this->random_id();
        }
        if (isset($data['name'])) {
            $this->name = $data['name'];
        } elseif ($this->name === '' && $this->label !== '') {
            $this->name = $this->autogenerate_name();
        }
        if (isset($data['value'])) {
            $this->value = $data['value'];
        }
        if (isset($data['db_value'])) {
            $this->db_value = $data['db_value'];
        }
        if (isset($data['db_values'])) {
            $this->db_values = $data['db_values'];
        }
        if (isset($data['multiple'])) {
            $this->multiple = $data['multiple'];
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
     * @param array $data
     * @return static
     */
    public static function init(array $data): Field
    {
        return new static($data);
    }

    /**
     * Get new field as HTML
     *
     * @param array $data
     * @return string
     */
    public static function html(array $data): string
    {
        return (static::init($data))->to_html();
    }

    /**
     * Display new field as HTML
     *
     * @param array $data
     */
    public static function display(array $data): void
    {
        echo static::html($data);
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get label
     *
     * @return Label|null
     */
    public function label(): ?Label
    {
        if ($this->label_obj) {
            return $this->label_obj;
        }
        if (!$this->label) {
            return null;
        }

        return $this->label_obj = Label::init([
            'for' => $this->id,
            'value' => $this->label,
        ]);
    }

    /************************************************************************************/
    // Autogenerate methods

    /**
     * Autogenerate ID based on label
     *
     * @return string
     */
    public function autogenerate_id(): string
    {
        return Convert::init($this->label)->t2s()->value();
    }

    /**
     * Autogenerate name based on label
     *
     * @return string
     */
    public function autogenerate_name(): string
    {
        return Convert::init($this->label)->t2k()->value();
    }

    /**
     * Autogenerate value based on label
     *
     * @return string
     */
    public function autogenerate_value(): string
    {
        return Convert::init($this->label)->t2k()->value();
    }

    /**
     * Generate random ID
     *
     * @return string
     */
    public function random_id(): string
    {
        return Generate::string();
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
        if ($this->label !== '') {
            $data['label'] = $this->label;
        }
        if ($this->id !== '') {
            $data['id'] = $this->id;
        }
        if ($this->name !== '') {
            $data['name'] = $this->name;
        }
        if ($this->value !== '') {
            $data['value'] = $this->value;
        }
        if ($this->db_value !== '') {
            $data['db_value'] = $this->db_value;
        }
        if (count($this->db_values) > 0) {
            $data['db_values'] = $this->db_values;
        }
        if ($this->multiple !== null) {
            $data['multiple'] = $this->multiple;
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
            $output[] = $this->get_id_html();
        }
        if ($this->name !== '') {
            $output[] = $this->get_name_html();
        }
        if (count($this->classes) > 0) {
            $output[] = $this->get_classes_html();
        }
        if (count($this->attributes) > 0) {
            $output[] = $this->get_attributes_html();
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
     * Get label
     *
     * @return string
     */
    public function get_label(): string
    {
        return $this->label;
    }

    /**
     * Set label
     *
     * @param string $label
     */
    public function set_label(string $label): void
    {
        $this->label = $label;
    }

    /*----------------------------------------------------------------------------------*/

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
        $name = $this->name;
        if ($this->multiple) {
            $name .= '[]';
        }
        return 'name="' . $name . '"';
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
        return $this->value;
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
     * Get database value
     *
     * @return string
     */
    public function get_db_value(): string
    {
        return $this->db_value;
    }

    /**
     * Set database value
     *
     * @param string $db_value
     */
    public function set_values(string $db_value): void
    {
        $this->db_value = $db_value;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get database values
     *
     * @return array
     */
    public function get_db_values(): array
    {
        return $this->db_values;
    }

    /**
     * Add database value (to db_values)
     *
     * @param int|string $db_value
     */
    public function add_db_value(int|string $db_value): void
    {
        $this->db_values[] = $db_value;
    }

    /**
     * Has database value (in db_values)?
     *
     * @param int|string $db_value
     * @return bool
     */
    public function has_db_value(int|string $db_value): bool
    {
        return in_array($db_value, $this->db_values);
    }

    /**
     * Set database values
     *
     * @param array $db_values
     */
    public function set_db_values(array $db_values): void
    {
        $this->db_values = $db_values;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Has multiple?
     *
     * @return bool
     */
    public function has_multiple(): bool
    {
        return $this->multiple;
    }

    /**
     * Set multiple
     *
     * @param bool $multiple
     */
    public function set_multiple(bool $multiple): void
    {
        $this->multiple = $multiple;
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
        return Convert::init($this->attributes)->a2a()->value();
    }

    /**
     * Add attribute
     *
     * @param string $value
     * @param string $key
     */
    public function add_attribute(string $value, string $key = ''): void
    {
        if ($key == '') {
            $this->attributes[] = $value;
        } else {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * Has attribute?
     *
     * @param string $attribute
     * @return bool
     */
    public function has_attribute(string $attribute): bool
    {
        foreach ($this->attributes as $key => $value) {
            if (is_integer($key) && $value == $attribute) {
                return true;
            }
            if (is_string($key) && $key == $attribute) {
                return true;
            }
        }

        return false;
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