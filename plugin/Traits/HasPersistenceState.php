<?php

namespace Charm\Traits;

use Charm\Contracts\IsPersistable;
use Charm\Enums\PersistenceState;
use Charm\Support\Result;

/**
 * Indicates that a model has a persistence state.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait HasPersistenceState
{
    /**
     * State of model
     *
     * @var PersistenceState
     */
    protected PersistenceState $state = PersistenceState::CLEAN;

    /**************************************************************************/

    /**
     * Mark model with state
     *
     * @param PersistenceState $state
     * @return $this
     */
    public function mark(PersistenceState $state): static
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Persist model based on state
     *
     * @return Result
     */
    public function persist(): Result
    {
        if (!$this instanceof IsPersistable) {
            return Result::error(
                'model_not_persistable',
                __('Ensure model implements `IsPersistable` interface.', 'charm')
            );
        }

        $result = match ($this->state) {
            PersistenceState::CLEAN => Result::success(),
            PersistenceState::NEW => $this->create(),
            PersistenceState::DIRTY => $this->update(),
            PersistenceState::DELETED => $this->delete(),
            default => Result::error(
                'state_not_recognized',
                __('Unknown model state cannot be persisted.', 'charm')
            )
        };

        if ($result->hasSucceeded()) {
            $this->state = PersistenceState::CLEAN;
        }

        return $result;
    }

    /**************************************************************************/

    /**
     * Get persistence state
     *
     * @return PersistenceState
     */
    public function getPersistenceState(): PersistenceState
    {
        return $this->state;
    }
}