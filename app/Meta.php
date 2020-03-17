<?php

namespace Charm\App;

use Charm\WordPress\Meta as WpMeta;

/**
 * Class Meta
 *
 * @author Ryan Sechrest
 * @package Charm\App
 */
class Meta extends WpMeta
{
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
    // Initialization methods

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
    // Cast methods

    /**
     * Return value as array
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
     * Return value as bool
     *
     * @return bool
     */
    public function bool(): bool
    {
        $bool = $this->meta_value;
        if (is_bool($bool)) {
            return $bool;
        }
        if (is_string($bool) && $bool === 'true') {
            return true;
        }
        if (is_numeric($bool) && $bool == 1) {
            return true;
        }

        return false;
    }

    /**
     * Return value as integer
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
     * Return value as text
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
}