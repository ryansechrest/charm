<?php

namespace Charm\FormElement;

/**
 * Class Select
 *
 * @author Ryan Sechrest
 * @package Charm\FormElement
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
     * Groups
     *
     * @var array
     */
    protected array $groups = [];

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
        if (isset($data['groups'])) {
            $this->groups = $data['groups'];
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
        if (count($this->groups) > 0) {
            $data['groups'] = $this->groups;
        }
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
        if (count($this->groups) > 0) {
            $output .= $this->get_groups_html();
        } else {
            $output .= $this->get_options_html();
        }
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
     * Get groups
     *
     * @return array
     */
    public function get_groups(): array
    {
        return $this->groups;
    }

    /**
     * Get groups as HTML
     *
     * @return string
     */
    public function get_groups_html(): string
    {
        if (count($this->groups) === 0) {
            return '';
        }
        $output = '';
        foreach ($this->groups as $group) {
            $has_label = isset($group['label']) &&  $group['label'] !== '';
            if ($has_label) {
                $output .= '<optgroup label="' . $group['label'] . '">';
            }
            if (isset($group['options']) && count($group['options']) > 0) {
                $this->options = $group['options'];
                $output .= $this->get_options_html();
                $this->options = [];
            }
            if ($has_label) {
                $output .= '</optgroup>';
            }
        }

        return $output;
    }

    /**
     * Add group
     *
     * @param array $group
     */
    public function add_group(array $group): void
    {
        $this->groups[] = $group;
    }

    /**
     * Set groups
     *
     * @param array $groups
     */
    public function set_groups(array $groups): void
    {
        $this->groups = $groups;
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
            $matches_single = !$this->multiple && $key == $this->db_value;
            $matches_multi = $this->multiple && $this->has_db_value($key);
            if ($matches_single || $matches_multi) {
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