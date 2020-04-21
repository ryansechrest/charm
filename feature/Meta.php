<?php

namespace Charm\Feature;

use Charm\App\Meta as MetaClass;

/**
 * Trait MetaMethods
 *
 * @author Ryan Sechrest
 * @package Charm\Feature
 */
trait Meta
{
    /************************************************************************************/
    // Properties

    /**
     * Post metas
     *
     * @var array
     */
    protected $metas = [];

    /************************************************************************************/
    // Get and set methods

    /**
     * Get or create meta(s)
     *
     * @param string $key
     * @param mixed $value
     * @return MetaClass|MetaClass[]
     */
    public function meta(string $key = '', $value = null)
    {
        if (count($this->metas) === 0) {
            $this->metas = $this->get_metas();
        }
        if ($value !== null) {
            $this->save_meta($key, $value);
        }

        return $this->get_meta($key);
    }

    /************************************************************************************/
    // Private get and set methods

    /**
     * Get post meta from Post
     *
     * @param string $key
     * @return MetaClass|MetaClass[]
     */
    private function get_meta(string $key = '')
    {
        if ($key === '') {
            return $this->metas;
        }
        if (!isset($this->metas[$key])) {
            return $this->create_meta($key, null);
        }

        return $this->metas[$key];
    }

    /**
     * Save post meta in Post
     *
     * @param string $key
     * @param mixed $value
     */
    private function save_meta(string $key, $value): void
    {
        if (isset($this->metas[$key]) && !is_array($this->metas[$key])) {
            $this->metas[$key]->set_meta_value($value);
            return;
        }
        $meta = $this->create_meta($key, $value);
        if (!isset($this->metas[$key])) {
            $this->metas[$key] = $meta;
            return;
        }
        if (is_array($this->metas[$key])) {
            $this->metas[$key][] = $meta;
            return;
        }
        $this->metas[$key] = [$this->metas[$key], $meta];
    }

    /**
     * Create post meta instance
     *
     * @param string $key
     * @param mixed $value
     * @return MetaClass
     */
    private function create_meta(string $key, $value): MetaClass
    {
        $meta = static::META;

        return new $meta([
            'meta_key' => $key,
            'meta_value' => $value,
        ]);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get post metas from database
     *
     * @return array
     */
    private function get_metas(): array
    {
        $metas = call_user_func(
            static::META . '::init', [
                'object_id' => $this->id,
            ]
        );
        if ($metas === null) {
            return [];
        }

        return $metas;
    }

    /**
     * Save post metas in database
     */
    private function save_metas(): void
    {
        if (count($this->metas) === 0) {
            return;
        }
        foreach ($this->metas as $key => $meta) {
            if (!is_array($meta)) {
                $meta->set_object_id($this->id);
                $meta->save();
                continue;
            }
            foreach ($meta as $single_meta) {
                $single_meta->set_object_id($this->id);
                $single_meta->save();
            }
        }
    }
}