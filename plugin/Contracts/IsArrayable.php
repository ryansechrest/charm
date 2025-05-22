<?php

namespace Charm\Contracts;

/**
 * Ensures that the model can be cast to an array.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface IsArrayable
{
    /**
     * Cast the model to an array.
     *
     * @param array $only Only include specified fields
     * @param array $except Exclude specified fields
     * @return array
     */
    public function toArray(array $only = [], array $except = []): array;
}