<?php

namespace Charm\Entity;

use Charm\Helper\Converter;
use Charm\WordPress\Meta as WpMeta;

/**
 * Class Meta
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class Meta extends WpMeta
{
    /************************************************************************************/
    // Constants

    /**
     * Meta type
     *
     * @var string
     */
    const META_TYPE = '';

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        $data['meta_type'] = static::META_TYPE;
        parent::load($data);
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize meta(s)
     *
     * @param array $params
     * @return array|static|static[]|null
     */
    public static function init(array $params)
    {
        $params['meta_type'] = static::META_TYPE;

        return parent::init($params);
    }

    /************************************************************************************/
    // Action methods

    /**
     * Add value to array
     *
     * @param mixed $value
     */
    public function add($value): void
    {
        if (!is_array($this->meta_value)) {
            return;
        }
        $this->meta_value[] = $value;
    }

    /************************************************************************************/
    // Cast methods

    /**
     * Cast value to array
     *
     * @see maybe_unserialize()
     * @return array
     */
    public function array(): array
    {
        $array = maybe_unserialize($this->meta_value);
        if ($array === null) {
            return [];
        }
        if (!is_array($array)) {
            return [$array];
        }

        return $array;
    }

    /**
     * Cast value to bool
     *
     * @return bool
     */
    public function bool(): bool
    {
        if (is_bool($this->meta_value)) {
            return $this->meta_value;
        }
        if (is_string($this->meta_value) && $this->meta_value === 'true') {
            return true;
        }
        if (is_numeric($this->meta_value) && $this->meta_value === 1) {
            return true;
        }

        return false;
    }

    /**
     * Cast value to integer
     *
     * @return int
     */
    public function int(): int
    {
        if (!is_numeric($this->meta_value)) {
            return 0;
        }

        return (int) $this->meta_value;
    }

    /**
     * Cast value to text
     *
     * @return string
     */
    public function text(): string
    {
        if (!$string = (string) $this->meta_value) {
            return '';
        }

        return $string;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Pass value to converter
     *
     * @return Converter
     */
    public function convert()
    {
        return Converter::init($this->meta_value);
    }
}