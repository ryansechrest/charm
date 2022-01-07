<?php

namespace Charm\Feature;

use Charm\Entity\Meta as MetaClass;

/**
 * Trait Meta
 *
 * @author Ryan Sechrest
 * @package Charm\Feature
 */
trait Meta
{
    /************************************************************************************/
    // Properties

    /**
     * Metas
     *
     * @var array
     */
    protected array $metas = [];

    /************************************************************************************/
    // Get and set methods

    /**
     * Get or create meta(s)
     *
     * @param string $key
     * @param mixed $value
     * @return MetaClass|MetaClass[]
     */
    public function meta(string $key = '', mixed $value = null): array|MetaClass
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
     * Get meta from object
     *
     * @param string $key
     * @return MetaClass|MetaClass[]
     */
    private function get_meta(string $key = ''): array|MetaClass
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
     * Save meta in object
     *
     * @param string $key
     * @param mixed $value
     */
    private function save_meta(string $key, mixed $value): void
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
     * Create meta instance
     *
     * @param string $key
     * @param mixed $value
     * @return MetaClass
     */
    private function create_meta(string $key, mixed $value): MetaClass
    {
        $meta = static::META;

        return new $meta([
            'meta_key' => $key,
            'meta_value' => $value,
        ]);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get metas from database
     *
     * @return array
     */
    private function get_metas(): array
    {
        $metas = call_user_func(
            static::META . '::get', [
                'object_id' => $this->id(),
            ]
        );
        if ($metas === null) {
            return [];
        }

        return $metas;
    }

    /**
     * Save metas in database
     */
    private function save_metas(): void
    {
        if (count($this->metas) === 0) {
            return;
        }
        foreach ($this->metas as $key => $meta) {
            if (!is_array($meta)) {
                $meta->set_object_id($this->id());
                $meta->save();
                continue;
            }
            foreach ($meta as $single_meta) {
                $single_meta->set_object_id($this->id());
                $single_meta->save();
            }
        }
    }
}