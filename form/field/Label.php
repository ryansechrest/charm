<?php

namespace Charm\Form\Field;

/**
 * Class Label
 *
 * @author Ryan Sechrest
 * @package Charm\Form\Field
 */
class Label
{
    /************************************************************************************/
    // Properties

    /**
     * For
     *
     * @var string
     */
    protected string $for = '';

    /**
     * Value
     *
     * @var string
     */
    protected string $value = '';

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Label constructor
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
        if (isset($data['for'])) {
            $this->for = $data['for'];
        }
        if (isset($data['value'])) {
            $this->value = $data['value'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * New label
     *
     * @param array $data
     * @return static
     */
    public static function init(array $data): Label
    {
        return new static($data);
    }

    /**
     * Get new label as HTML
     *
     * @param array $data
     * @return string
     */
    public static function html(array $data): string
    {
        return (static::init($data))->to_html();
    }

    /**
     * Display new label as HTML
     *
     * @param array $data
     */
    public static function display(array $data): void
    {
        echo static::html($data);
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
        if ($this->for !== '') {
            $data['for'] = $this->for;
        }
        if ($this->value !== '') {
            $data['value'] = $this->value;
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
        return '<label ' . $this->get_for_html() . '>' . $this->value . '</label>';
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
     * Get for
     *
     * @return string
     */
    public function get_for(): string
    {
        return $this->for;
    }

    /**
     * Get for as HTML
     *
     * @return string
     */
    public function get_for_html(): string
    {
        return 'for="' . $this->for . '"';
    }

    /**
     * Set for
     *
     * @param string $for
     */
    public function set_for(string $for): void
    {
        $this->for = $for;
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
     * Set value
     *
     * @param string $value
     */
    public function set_value(string $value): void
    {
        $this->value = $value;
    }
}