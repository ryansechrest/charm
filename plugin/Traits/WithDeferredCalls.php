<?php

namespace Charm\Traits;

use Charm\Support\Result;

/**
 * Adds support for deferring method calls for later execution.
 *
 * Traits like `WithMetas` and `WithRole` provide functionality for managing
 * related data (e.g., metadata or roles) within a model like `Post` or `User`.
 *
 * These traits define methods such as `createMeta()`, `updateMeta()`, or
 * `setRole()` that modify internal state, along with corresponding persistence
 * methods like `persistMetas()` or `persistRole()`.
 *
 * To allow a model to persist all such changes without needing to know which
 * traits it uses, this trait provides a centralized mechanism for deferring
 * method calls until a later time.
 *
 * Whenever a trait makes a change, it can register its persistence method,
 * e.g. `persistMetas()` or `persistRole()`, using `registerDeferred()`. Later,
 * the model can call `runDeferred()` to execute all registered methods.
 *
 * This decouples traits from models and enables a unified persistence flow
 * without tight coupling or assumptions about which traits are present.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithDeferredCalls
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
    protected function runDeferred(): array
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