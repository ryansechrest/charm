<?php

namespace Charm\Helper;

/**
 * Class Convert
 *
 * @author Ryan Sechrest
 * @package Charm\Helper
 */
class Convert
{
    /************************************************************************************/
    // Properties

    /**
     * Value
     *
     * @var mixed
     */
    protected $value = null;

    /**
     * Original value
     *
     * @var mixed
     */
    protected $original_value = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Convert constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (count($data) === 0) {
            return;
        }
        $this->load($data);
    }

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['value'])) {
            $this->value = $data['value'];
        }
        if (isset($data['original_value'])) {
            $this->original_value = $data['original_value'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize convert
     *
     * @param mixed $value
     * @return self
     */
    public static function init($value): self
    {
        return new static([
            'value' => $value,
            'original_value' => $value,
        ]);
    }

    /************************************************************************************/
    // Chainable conversion methods

    /**
     * Convert (t)ext (a)rea value to (a)rray
     *  e.g. "hello" -> ["hello", "world"]
     *       "world"
     *
     * @param string $row_delimiter
     * @param string $column_delimiter
     * @return self
     */
    public function ta2a($row_delimiter = "\n", $column_delimiter = ''): self
    {
        if ($this->value === '') {
            $this->value = [];
            return $this;
        }
        $this->value = array_map('trim', explode($row_delimiter, $this->value));
        if ($column_delimiter === '') {
            return $this;
        }
        $this->value = array_map(function($row) use ($column_delimiter) {
            return array_map('trim', explode($column_delimiter, $row));
        }, $this->value);

        return $this;
    }

    /**
     * Convert (a)rray value to (d)ata (a)ttribute
     *  e.g. ["hello", "world"] -> "["hello", "world"]"
     *
     * @return self
     */
    public function a2da(): self
    {
        if (count($this->value) === 0) {
            $this->value = '';
            return $this;
        }
        $attributes = [];
        foreach ($this->value as $item) {
            if (!is_array($item)) {
                $attributes[] = '"' . $item . '"';
                continue;
            }
            $pieces = [];
            foreach ($item as $piece) {
                $pieces[] = '"' . $piece . '"';
            }
            $attributes[] = '[' . implode(',', $pieces) . ']';
        }
        $this->value = '[' . implode(',', $attributes) . ']';

        return $this;
    }

    /**
     * Convert (t)ext to (s)lug
     *  e.g. "Hello World" -> "hello-world"
     *
     * @return self
     */
    public function t2s(): self
    {
        $this->value = strtolower($this->value);
        $this->value = str_replace(' ', '-', $this->value);

        return $this;
    }

    /**
     * Convert (t)ext to (k)ey
     *  e.g. "Hello World" -> "hello_world"
     *
     * @return self
     */
    public function t2k(): self
    {
        $this->value = strtolower($this->value);
        $this->value = str_replace(' ', '_', $this->value);

        return $this;
    }

    /**
     * Return value in current state
     *
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get value
     *
     * @return mixed
     */
    public function get_value()
    {
        return $this->value;
    }

    /**
     * Set value
     *
     * @param mixed $value
     */
    public function set_value($value)
    {
        $this->value = $value;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get original value
     *
     * @return mixed
     */
    public function get_original_value()
    {
        return $this->original_value;
    }

    /**
     * Set original value
     *
     * @param mixed $value
     */
    public function set_original_value($value)
    {
        $this->original_value = $value;
    }
}