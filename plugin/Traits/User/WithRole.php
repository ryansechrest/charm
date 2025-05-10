<?php

namespace Charm\Traits\User;

use Charm\Contracts\HasDeferredCalls;
use Charm\Contracts\HasWpUser;
use Charm\Structures\Role;
use Charm\Support\Result;

/**
 * Adds role to user model.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithRole
{
    /**
     * Role to assign user.
     *
     * @var ?Role
     */
    protected ?Role $pendingRole = null;

    // *************************************************************************

    /**
     * Get role
     *
     * @return ?Role
     */
    public function getRole(): ?Role
    {
        /** @var HasWpUser $this */
        return Role::init($this->wp()->core()->roles[0] ?? '');
    }

    /**
     * Set role
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
     * Persist pending role
     *
     * @return Result
     */
    protected function persistRole(): Result
    {
        if ($this->pendingRole === null) {
            return Result::error(
                code: 'role_not_persisted',
                message: __('No valid role specified to be persisted.', 'charm')
            );
        }

        if (!$this->pendingRole->exists()) {
            return Result::error(
                code: 'role_not_found',
                message: __('Role to be persisted does not exist.', 'charm')
            );
        }

        /** @var HasWpUser $this */
        $this->wp()->core()->set_role($this->pendingRole->getSlug());

        $this->pendingRole = null;

        return Result::success();
    }
}