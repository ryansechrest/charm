<?php

namespace Charm\Contracts;

use Charm\Support\Result;

/**
 * Ensures that the model can be persisted.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface IsPersistable
{
    /**
     * Save model in database
     *
     * @return Result
     */
    public function save(): Result;

    /**
     * Create model in database
     *
     * @return Result
     */
    public function create(): Result;

    /**
     * Update model in database
     *
     * @return Result
     */
    public function update(): Result;

    /**
     * Delete model from database
     *
     * @return Result
     */
    public function delete(): Result;
}
