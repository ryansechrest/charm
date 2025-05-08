<?php

namespace Charm\Contracts;

use Charm\Support\Result;

/**
 * Ensures that the model can defer persistence.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasDeferredPersistence
{
    /**
     * Register a persistence method to be executed later.
     *
     * @param string $method
     * @return void
     */
    function registerDeferred(string $method): void;

    /**
     * Execute all registered persistence methods.
     *
     * @return Result[]
     */
    function persistDeferred(): array;
}