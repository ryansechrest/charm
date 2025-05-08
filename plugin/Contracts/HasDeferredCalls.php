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
     * Register a method to be executed later.
     *
     * @param string $method
     * @return void
     */
    function registerDeferred(string $method): void;

    /**
     * Execute all registered methods.
     *
     * @return Result[]
     */
    function runDeferred(): array;
}