<?php

namespace Charm\App\Feature;

/**
 * Interface Conversion
 *
 * @author Ryan Sechrest
 * @package Charm\App\Feature
 */
interface Conversion
{
    /**
     * Convert instance to array
     *
     * @return array
     */
    public function to_array(): array;

    /**
     * Convert instance to JSON
     *
     * @return string
     */
    public function to_json(): string;

    /**
     * Convert instance to object
     *
     * @return object
     */
    public function to_object(): object;
}