<?php

namespace Charm\Traits;

use Charm\Support\Result;

/**
 * Enables a model to register deferred persistence methods from traits.
 *
 * Traits can call `registerPersistenceMethod()` to add their persisters.
 * The main model can then call `$this->persistDeferred()` to execute them.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithDeferredPersistence
{
    /**
     * List of deferred persistence method names to call
     *
     * @var array<string>
     */
    protected array $persistenceMethods = [];

    // *************************************************************************

    /**
     * Register a persistence method to be executed later
     *
     * @param string $method
     * @return void
     */
    protected function registerPersistenceMethod(string $method): void
    {
        if (!in_array($method, $this->persistenceMethods, true)) {
            $this->persistenceMethods[] = $method;
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Execute all registered persistence methods
     *
     * @return Result[]
     */
    protected function persistDeferred(): array
    {
        $results = [];

        foreach ($this->persistenceMethods as $method) {
            if (!method_exists($this, $method)) {
                continue;
            }
            $results[] = $this->$method();
        }

        return $results;
    }
}