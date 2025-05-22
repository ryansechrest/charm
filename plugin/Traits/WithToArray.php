<?php

namespace Charm\Traits;

use Charm\Support\Filter;
use ReflectionObject;

/**
 * Adds support for managing terms on a model.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithToArray
{
    /**
     * Cast the model to an array.
     *
     * @param array $only Only include specified fields
     * @param array $except Exclude specified fields
     * @return array
     */
    public function toArray(array $only = [], array $except = []): array
    {
        $data = [];

        // Reflect on the current model to get its properties
        $reflection = new ReflectionObject($this);
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $name = $property->getName();
            $data[$name] = $property->getValue($this);
        }

        // Apply $only filter
        if (count($only) > 0) {
            return Filter::array($data)->only($only)->get();
        }

        // Apply $except filter
        if (count($except) > 0) {
            return Filter::array($data)->except($except)->get();
        }

        return $data;
    }
}