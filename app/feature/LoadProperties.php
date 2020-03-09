<?php

namespace Charm\App\Feature;

/**
 * Trait LoadProperties
 *
 * @author Ryan Sechrest
 * @package Charm\App\Feature
 */
trait LoadProperties
{
    /**
     * Class constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (!is_array($data)) {
            return;
        }
        $this->load($data);
    }

    /**
     * Load instance properties with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (!is_array($data)) {
            return;
        }
        foreach ($data as $property => $object) {
            if (!property_exists($this, $property)) {
                continue;
            }
            $this->$property = $object;
        }
    }
}