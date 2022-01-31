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
    protected string $type = '';

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
        if (count($this->attributes) > 0) {
            $data['attributes'] = $this->attributes;
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
        $output = '<input type="' . $this->type . '"';
        $output .= parent::to_html();
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
     * Set type
     *
     * @param string $type
     */
    public function set_type(string $type): void
    {
        $this->type = $type;
    }
}