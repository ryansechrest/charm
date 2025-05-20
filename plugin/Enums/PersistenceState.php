<?php

namespace Charm\Enums;

/**
 * Indicates the persistence state of a model.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
enum PersistenceState
{
    // The model matches the database
    case Clean;

    // The model does not exist in the database
    case New;

    // The model has changes that need to be written to the database
    case Dirty;

    // The model needs to be deleted from the database
    case Deleted;
}