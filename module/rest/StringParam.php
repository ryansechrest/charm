<?php

namespace Charm\Module\Rest;

/**
 * Class StringParam
 *
 * @author Ryan Sechrest
 * @package Charm\Module\Rest
 */
class StringParam extends Param
{
    /************************************************************************************/
    // Properties

    /**
     * Format
     *
     * Options: 'hex-color', 'date-time', 'email', 'ip', 'uuid'
     *
     * @var string
     */
    protected $format = '';

    /**
     * Pattern
     *
     * @var string
     */
    protected $pattern = '';

    /**
     * Min length
     *
     * @var int
     */
    protected $min_length = 0;

    /**
     * Max length
     *
     * @var int
     */
    protected $max_length = 0;

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
        $this->type = 'string';
        if (isset($data['format'])) {
            $this->format = $data['format'];
        }
        if (isset($data['pattern'])) {
            $this->pattern = $data['pattern'];
        }
        if (isset($data['min_length'])) {
            $this->min_length = $data['min_length'];
        }
        if (isset($data['max_length'])) {
            $this->max_length = $data['max_length'];
        }
    }

    /************************************************************************************/
    // Cast methods

    /**
     * Cast properties to array
     *
     * @return array
     */
    public function to_array(): array
    {
        $data = parent::to_array();
        if ($this->format !== '') {
            $data['format'] = $this->format;
        }
        if ($this->pattern !== '') {
            $data['pattern'] = $this->pattern;
        }
        if ($this->min_length !== 0) {
            $data['min_length'] = $this->min_length;
        }
        if ($this->max_length !== 0) {
            $data['max_length'] = $this->max_length;
        }

        return $data;
    }

    /**
     * Cast properties to array for WordPress
     *
     * @return array
     */
    public function to_array_for_wp(): array
    {
        $data = parent::to_array();
        if ($this->format !== '') {
            $data['format'] = $this->format;
        }
        if ($this->pattern !== '') {
            $data['pattern'] = $this->pattern;
        }
        if ($this->min_length !== 0) {
            $data['minLength'] = $this->min_length;
        }
        if ($this->max_length !== 0) {
            $data['maxLength'] = $this->max_length;
        }

        return $data;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get format
     *
     * @return string
     */
    public function get_format(): string
    {
        return $this->format;
    }

    /**
     * Set format
     *
     * @param string $format
     */
    public function set_format(string $format): void
    {
        $this->format = $format;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get pattern
     *
     * @return string
     */
    public function get_pattern(): string
    {
        return $this->pattern;
    }

    /**
     * Set pattern
     *
     * @param string $pattern
     */
    public function set_pattern(string $pattern): void
    {
        $this->pattern = $pattern;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get min length
     *
     * @return int
     */
    public function get_min_length(): int
    {
        return $this->min_length;
    }

    /**
     * Set min length
     *
     * @param int $min_length
     */
    public function set_min_length(int $min_length): void
    {
        $this->min_length = $min_length;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get max length
     *
     * @return int
     */
    public function get_max_length(): int
    {
        return $this->max_length;
    }

    /**
     * Set max length
     *
     * @param int $max_length
     */
    public function set_max_length(int $max_length): void
    {
        $this->max_length = $max_length;
    }
}