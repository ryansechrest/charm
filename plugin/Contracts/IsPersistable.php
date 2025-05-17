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
     * Saves the model in the database.
     *
     * @return Result
     */
    public function save(): Result;

    /**
     * Creates the model in the database.
     *
     * @return Result
     */
    public function create(): Result;

    /**
     * Updates the model in the database.
     *
     * @return Result
     */
    public function update(): Result;

    /**
     * Deletes the model from the database.
     *
     * @return Result
     */
    public function delete(): Result;
}
