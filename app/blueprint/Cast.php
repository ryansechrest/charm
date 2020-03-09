<?php

namespace Charm\App\Blueprint;

/**
 * Interface Conversion
 *
 * @author Ryan Sechrest
 * @package Charm\App\Blueprint
 */
interface Cast
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