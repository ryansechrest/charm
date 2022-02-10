<?php

namespace Charm\FormElement;

/**
 * Class Checkbox
 *
 * @author Ryan Sechrest
 * @package Charm\FormElement
 */
class Checkbox extends Field
{
    /************************************************************************************/
    // Constants

    /**
     * Type
     *
     * @var string
     */
    const TYPE = 'checkbox';

    /************************************************************************************/
    // Properties

    /**
     * Checked
     *
     * @var bool
     */
    protected bool $checked = false;

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
        if (!isset($data['value'])) {
            $this->value = $this->autogenerate_value();
        }
        if (isset($data['checked'])) {
            $this->checked = $data['checked'];
        }
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
        $data['checked'] = $this->checked;
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
        $output = '<input type="' . static::TYPE . '"';
        $attributes = [];
        if ($this->value !== '') {
            $attributes[] = $this->get_value_html();
        }
        $matches_single = !$this->multiple && $this->value == $this->db_value;
        $matches_multi = $this->multiple && $this->has_db_value($this->value);
        if ($this->checked === true || $matches_single || $matches_multi) {
            $attributes[] = 'checked';
        }
        $attributes[] = parent::to_html();
        $output .= implode(' ', $attributes);
        $output .= ' />';

        return $output;
    }

    /**
     * Cast instance to HTML in label
     *
     * @return string
     */
    public function to_html_in_label(): string
    {
        return $this->label()->to_html_open()
            . $this->to_html()
            . ' '
            . $this->label()->get_value()
            . $this->label()->to_html_close();
    }

    /**
     * Cast instance to HTML with label
     *
     * @return string
     */
    public function to_html_with_label(): string
    {
        return $this->to_html() .  ' ' . $this->label()->to_html();
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Is checked?
     *
     * @return bool
     */
    public function is_checked(): bool
    {
        return $this->checked;
    }

    /**
     * Check checkbox
     */
    public function check(): void
    {
        $this->checked = true;
    }

    /**
     * Uncheck checkbox
     */
    public function uncheck(): void
    {
        $this->checked = false;
    }

    /**
     * Set checked
     *
     * @param bool $checked
     */
    public function set_checked(bool $checked): void
    {
        $this->checked = $checked;
    }
}