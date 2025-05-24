<?php

namespace Charm\Traits;

use Charm\Contracts\IsPersistable;
use Charm\Enums\PersistenceState;
use Charm\Support\Result;

/**
 * Adds persistence state tracking to a model.
 *
 * A model, like `Post` or `User`, will immediately persist changes to the
 * database via one of its persistence methods, such as `create()` or
 * `update()`.
 *
 * When a model (e.g. `Post`) changes other models (e.g. `Meta`), we may want to
 * defer persisting changes to `Meta` models until a persistence method on the
 * `Post` model is called.
 *
 * This avoids having to manually call a persistence method on each `Meta` model
 * and ensures that `Meta` models don't change before the `Post` model.
 *
 * We achieve this by tracking the persistence state within each `Meta` model
 * and calling a unified `persist()` method, which automatically invokes the
 * appropriate persistence method based on the model’s state.
 *
 * @package Charm
 * @author Ryan Sechrest
 */
trait WithPersistenceState
{
    /**
     * Current persistence state of the model.
     *
     * @var PersistenceState
     */
    protected PersistenceState $state = PersistenceState::Clean;

    // *************************************************************************

    /**
     * Mark the model with a new persistence state.
     *
     * Use this to indicate that the model has been created, updated, or
     * deleted, but should not yet be persisted.
     *
     * @param PersistenceState $state
     * @return static
     */
    public function mark(PersistenceState $state): static
    {
        $this->state = $state;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Persist the model based on its persistence state.
     *
     * Executes the appropriate method, e.g. `create()`, `update()`, `delete()`,
     * based on the internal state. If the state is `Clean`, no action is taken.
     *
     * @return Result
     */
    public function persist(): Result
    {
        if (!$this instanceof IsPersistable) {
            return Result::error(
                'model_not_persistable',
                'Model does not implement `IsPersistable` interface.'
            );
        }

        $result = match ($this->state) {
            PersistenceState::Clean => Result::info('model_not_changed', 'Model was not persisted since it did not change.'),
            PersistenceState::New => $this->create(),
            PersistenceState::Dirty => $this->update(),
            PersistenceState::Deleted => $this->delete(),
            default => Result::error('persistence_state_unknown', 'Model was not persisted due to unknown persistence state.')
        };

        if ($result->hasSucceeded()) {
            $this->state = PersistenceState::Clean;
        }

        return $result;
    }

    // *************************************************************************

    /**
     * Get the current persistence state.
     *
     * @return PersistenceState
     */
    public function getPersistenceState(): PersistenceState
    {
        return $this->state;
    }
}