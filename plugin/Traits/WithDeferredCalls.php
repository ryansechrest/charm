<?php

namespace Charm\Traits;

use Charm\Support\Result;

/**
 * Adds deferred method execution to a model.
 *
 * Traits can call `registerDeferred()` to queue methods with arguments for
 * later execution. The parent object can then call `persistDeferred()` to run
 * them all.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithDeferredPersistence
{
    /**
     * List of deferred method names and their arguments.
     *
     * @var array<string, array<int, mixed>>
     */
    protected array $deferredMethods = [];

    // *************************************************************************

    /**
     * Register a method with arguments to be executed later.
     *
     * @param string $method
     * @param mixed ...$args
     * @return void
     */
    protected function registerDeferred(string $method, mixed ...$args): void
    {
        $this->deferredMethods[$method] = $args;
    }

    // -------------------------------------------------------------------------

    /**
     * Execute all registered deferred methods and return results.
     *
     * @return Result[]
     */
    protected function persistDeferred(): array
    {
        $results = [];

        foreach ($this->deferredMethods as $method => $args) {
            if (!method_exists($this, $method)) {
                continue;
            }

            $result = $this->$method(...$args);

            if ($result instanceof Result) {
                $results[] = $result;
                continue;
            }

            $results = array_merge($results, $result);
        }

        $this->deferredMethods = [];

        return $results;
    }
}