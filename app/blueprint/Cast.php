<?php

namespace Charm\App\Blueprint;

/**
 * Interface Cast
 *
 * @author Ryan Sechrest
 * @package Charm\App\Blueprint
 */
interface Cast
{
    /**
     * Cast instance to array
     *
     * @return array
     */
    public function to_array(): array;

    /**
     * Cast instance to JSON
     *
     * @return string
     */
    public function to_json(): string;

    /**
     * Cast instance to object
     *
     * @return object
     */
    public function to_object(): object;
}