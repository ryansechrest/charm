<?php

namespace Charm\Traits\User;

use Charm\Contracts\HasDeferredPersistence;
use Charm\Contracts\HasWpUser;
use Charm\Structures\Role;
use Charm\Support\Result;

/**
 * Indicates that a user has a role.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithRole
{
    /**
     * Role to assign user
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
        /** @var HasDeferredPersistence $this */

        $this->pendingRole = is_string($role) ? Role::init($role) : $role;

        $this->registerPersistenceMethod('persistRole');

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
        /** @var HasWpUser $this */

        if ($this->pendingRole === null) {
            return Result::error(
                'role_not_persisted',
                __('No valid role specified to be persisted.', 'charm')
            );
        }

        if (!$this->pendingRole->exists()) {
            return Result::error(
                'role_not_found',
                __('Role to be persisted does not exist.', 'charm')
            );
        }

        $this->wp()->core()->set_role($this->pendingRole->getSlug());

        $this->pendingRole = null;

        return Result::success();
    }
}