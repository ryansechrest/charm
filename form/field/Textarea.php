<?php

namespace Charm\Form\Field;

/**
 * Class Textarea
 *
 * @author Ryan Sechrest
 * @package Charm\Form\Field
 */
class Textarea extends Field
{
    /************************************************************************************/
    // Properties

    /**
     * Rows
     *
     * @var int
     */
    protected int $rows = 0;

    /**
     * Columns
     *
     * @var int
     */
    protected int $cols = 0;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['rows'])) {
            $this->rows = $data['rows'];
        }
        if (isset($data['cols'])) {
            $this->cols = $data['cols'];
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
        if ($this->rows !== 0) {
            $data['rows'] = $this->rows;
        }
        if ($this->cols !== 0) {
            $data['cols'] = $this->cols;
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
        $output = '<textarea ';
        $attributes[] = parent::to_html();
        if ($this->rows !== 0) {
            $attributes[] = $this->get_rows_html();
        }
        if ($this->cols !== 0) {
            $attributes[] = $this->get_cols_html();
        }
        $output .= implode(' ', $attributes);
        $output .= '>';
        $output .= $this->value;
        $output .= '</textarea>';

        return $output;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get rows
     *
     * @return int
     */
    public function get_rows(): int
    {
        return $this->rows;
    }

    /**
     * Get rows as HTML
     *
     * @return string
     */
    public function get_rows_html(): string
    {
        return 'rows="' . $this->rows . '"';
    }

    /**
     * Set type
     *
     * @param int $rows
     */
    public function set_rows(int $rows): void
    {
        $this->rows = $rows;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get columns
     *
     * @return int
     */
    public function get_cols(): int
    {
        return $this->cols;
    }

    /**
     * Get columns as HTML
     *
     * @return string
     */
    public function get_cols_html(): string
    {
        return 'cols="' . $this->cols . '"';
    }

    /**
     * Set columns
     *
     * @param int $cols
     */
    public function set_cols(int $cols): void
    {
        $this->cols = $cols;
    }
}