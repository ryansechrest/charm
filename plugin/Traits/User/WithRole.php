<?php

namespace Charm\Traits\User;

use Charm\Contracts\HasDeferredCalls;
use Charm\Contracts\Core\HasCoreUser;
use Charm\Models\Role;
use Charm\Support\Result;

/**
 * Adds the role to a user model.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithRole
{
    /**
     * Role to be assigned to the user.
     *
     * @var ?Role
     */
    protected ?Role $pendingRole = null;

    // *************************************************************************

    /**
     * Get the user's role.
     *
     * @return ?Role
     */
    public function getRole(): ?Role
    {
        /** @var HasCoreUser $this */
        return Role::init($this->coreUser()->wpUser()->roles[0] ?? '');
    }

    /**
     * Set the user's role.
     *
     * @param Role|string $role
     * @return static
     */
    public function setRole(Role|string $role): static
    {
        $this->pendingRole = is_string($role) ? Role::init($role) : $role;

        /** @var HasDeferredCalls $this */
        $this->registerDeferred(method: 'persistRole');

        return $this;
    }

    // *************************************************************************

    /**
     * Assign the pending role to the user.
     *
     * @return Result
     */
    protected function persistRole(): Result
    {
        $args = ['pendingRole' => $this->pendingRole];

        if ($this->pendingRole === null) {
            return Result::info(
                'role_not_updated',
                'There was no pending role to be persisted.',
            )->setFunctionArgs($args);
        }

        if (!$this->pendingRole->exists()) {
            return Result::error(
                'role_not_found',
                'Role to be persisted does not exist.',
            )->setFunctionArgs($args);
        }

        /** @var HasCoreUser $this */
        $this->coreUser()->wpUser()->set_role($this->pendingRole->getSlug());

        $this->pendingRole = null;

        return Result::success(
            'role_persist_success',
            'Role successfully persisted.'
        )->setFunctionArgs($args);
    }
}