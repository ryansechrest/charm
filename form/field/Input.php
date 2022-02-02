<?php

namespace Charm\Form\Field;

/**
 * Class Input
 *
 * @author Ryan Sechrest
 * @package Charm\Form\Field
 */
class Input extends Field
{
    /************************************************************************************/
    // Properties

    /**
     * Type
     *
     * @var string
     */
    protected string $type = 'text';

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['type'])) {
            $this->type = $data['type'];
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
        if ($this->type !== '') {
            $data['type'] = $this->type;
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
        $output = '<input ';
        $attributes[] = $this->get_type_html();
        if ($this->value !== '') {
            $attributes[] = $this->get_value_html();
        }
        $attributes[] = parent::to_html();
        $output .= implode(' ', $attributes);
        $output .= ' />';

        return $output;
    }

    /************************************************************************************/
    // Get and set methods

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
     * Get type as HTML
     *
     * @return string
     */
    public function get_type_html(): string
    {
        return 'type="' . $this->type . '"';
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
}