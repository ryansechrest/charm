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
    // Model matches database
    case CLEAN;

    // Model does not exist in database
    case NEW;

    // Model has changes that need to be written to database
    case DIRTY;

    // Model needs to be deleted from database
    case DELETED;
}