<?php

namespace Charm\Contracts;

use Charm\Support\Result;

/**
 * Ensures model implements methods required for persistence.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface IsPersistable
{
    /**
     * Get model ID
     *
     * @return int
     */
    public function getId(): int;

    // -------------------------------------------------------------------------

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
