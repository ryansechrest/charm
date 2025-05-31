<?php

namespace Charm\Contracts\Core;

use Charm\Models\Core;

/**
 * Ensures that the model implements a `Core\Term` instance.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
interface HasCoreTerm
{
    /**
     * Provides access to the `Core\Term` instance.
     *
     * @return ?Core\Term
     */
    public function coreTerm(): ?Core\Term;
}