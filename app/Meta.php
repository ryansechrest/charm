<?php

namespace Charm\App;

use Charm\App\Feature\Cast;
use Charm\App\Feature\LoadProperties;
use Charm\WordPress\Meta as WpMeta;

/**
 * Class Post
 *
 * @author Ryan Sechrest
 * @package Charm\App
 */
class Meta
{
    use Cast, LoadProperties;

    /**
     * WordPress meta
     *
     * @var WpMeta
     */
    protected $wp_meta = null;

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize meta(s)
     *
     * @param array $params
     * @return array|Meta|Meta[]|null
     */
    public static function init(array $params)
    {
        $data = [];
        $data['wp_meta'] = WpMeta::init($params);
        if ($data['wp_meta'] === null) {
            return null;
        }

        return new Meta($data);
    }

    /**
     * Get metas
     *
     * @todo Implement Meta::get()
     * @param array $params
     * @return Post[]
     */
    public static function get(array $params): array
    {
        return [];
    }

    /************************************************************************************/
    // Action methods

    /**
     * Save meta
     *
     * @return bool
     */
    public function save(): bool
    {
        return $this->wp_meta->save();
    }

    /**
     * Create meta
     *
     * @return bool
     */
    public function create(): bool
    {
        return $this->wp_meta->create();
    }

    /**
     * Update meta
     *
     * @return bool
     */
    public function update(): bool
    {
        return $this->wp_meta->update();
    }

    /**
     * Delete meta
     *
     * @return bool
     */
    public function delete(): bool
    {
        return $this->wp_meta->delete();
    }

    /************************************************************************************/
    // Value access methods

    /**
     * Return value as array
     *
     * @see maybe_unserialize()
     * @return array
     */
    public function array(): array
    {
        $array = maybe_unserialize($this->wp_meta->get_meta_value());
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
        $bool = $this->wp_meta->get_meta_value();
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
        if (!is_numeric($this->wp_meta->get_meta_value())) {
            return 0;
        }

        return (int) $this->wp_meta->get_meta_value();
    }

    /**
     * Return value as text
     *
     * @return string
     */
    public function text(): string
    {
        if (!$string = (string) $this->wp_meta->get_meta_value()) {
            return '';
        }

        return $string;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get ID
     *
     * @return int
     */
    public function get_id(): int
    {
        return $this->wp_meta->get_meta_id();
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get key
     *
     * @return string
     */
    public function get_key(): string
    {
        return $this->wp_meta->get_meta_key();
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get value
     *
     * @return mixed
     */
    public function get_value()
    {
        return $this->wp_meta->get_meta_value();
    }

    /**
     * Set value
     *
     * @param $value
     */
    public function set_value($value): void
    {
        $this->wp_meta->set_meta_value($value);
    }
}