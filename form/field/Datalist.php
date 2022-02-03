<?php

namespace Charm\Form\Field;

/**
 * Class Datalist
 *
 * @author Ryan Sechrest
 * @package Charm\Form\Field
 */
class Datalist extends Field
{
    /************************************************************************************/
    // Properties

    /**
     * Options
     *
     * @var array
     */
    protected array $options = [];

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['options'])) {
            $this->options = $data['options'];
        }
        parent::load($data);
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
        if (count($this->options) > 0) {
            $data['options'] = $this->options;
        }
        array_merge($data, parent::to_array());

        return $data;
    }

    /**
     * Cast instance to HTML
     *
     * @return string
     */
    public function to_html(): string
    {
        $output = '<input list="' . $this->id . '"';
        if ($this->value) {
            $output .= ' ' . $this->get_value_html();
        }
        $output .= '>';
        $output .= '<datalist id="' . $this->id . '" ' . parent::to_html() . '>';
        $output .= $this->get_options_html();
        $output .= '</datalist>';

        return $output;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get options
     *
     * @return array
     */
    public function get_options(): array
    {
        return $this->options;
    }

    /**
     * Get options as HTML
     *
     * @return string
     */
    public function get_options_html(): string
    {
        if (count($this->options) === 0) {
            return '';
        }
        $output = '';
        foreach ($this->options as $value) {
            $output .= '<option value="' . $value . '">';
        }

        return $output;
    }

    /**
     * Add option
     *
     * @param int|string $key
     * @param string $value
     */
    public function add_option(int|string $key, string $value): void
    {
        $this->options[$key] = $value;
    }

    /**
     * Has option?
     *
     * @param int|string $key
     * @return bool
     */
    public function has_option(int|string $key): bool
    {
        return array_key_exists($key, $this->options);
    }

    /**
     * Set options
     *
     * @param array $options
     */
    public function set_options(array $options): void
    {
        $this->options = $options;
    }
}