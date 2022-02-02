<?php

namespace Charm\Form\Field;

/**
 * Class Select
 *
 * @author Ryan Sechrest
 * @package Charm\Form\Field
 */
class Select extends Field
{
    /************************************************************************************/
    // Properties

    /**
     * Size
     *
     * @var int
     */
    protected int $size = 0;

    /**
     * Multiple
     *
     * @var bool
     */
    protected bool $multiple = false;

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
        if (isset($data['size'])) {
            $this->size = $data['size'];
        }
        if (isset($data['multiple'])) {
            $this->multiple = $data['multiple'];
        }
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
        if ($this->size !== 0) {
            $data['size'] = $this->size;
        }
        $data['multiple'] = $this->multiple;
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
        $output = '<select ';
        $attributes[] = parent::to_html();
        if ($this->size > 0) {
            $attributes[] = $this->get_size_html();
        }
        if ($this->multiple === true) {
            $attributes[] = 'multiple';
        }
        $output .= implode(' ', $attributes);
        $output .= '>';
        $output .= $this->get_options_html();
        $output .= '</select>';

        return $output;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get size
     *
     * @return int
     */
    public function get_size(): int
    {
        return $this->size;
    }

    /**
     * Get size as HTML
     *
     * @return string
     */
    public function get_size_html(): string
    {
        if ($this->size === 0) {
            return '';
        }

        return 'size="' . $this->size . '"';
    }

    /**
     * Set size
     *
     * @param int $size
     */
    public function set_size(int $size): void
    {
        $this->size = $size;
    }

    /*----------------------------------------------------------------------------------*/

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
        foreach ($this->options as $key => $value) {
            $output .= '<option value="' . $key . '"';
            if ($key == $this->value) {
                $output .= ' selected';
            }
            $output .= '>';
            $output .= $value;
            $output .= '</option>';
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