<?php

namespace Charm\Contracts;

use Charm\Support\Result;

/**
 * Ensures that the model can defer method execution.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasDeferredCalls
{
    /**
     * Registers a method to be executed later.
     *
     * @param string $method
     * @return void
     */
    function registerDeferred(string $method): void;

    /**
     * Executes all registered methods.
     *
     * @return Result[]
     */
    function runDeferred(): array;
}