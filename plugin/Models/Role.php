<?php

namespace Charm\Models;

use Charm\Contracts\IsPersistable;
use Charm\Contracts\Core\HasCoreRole;
use Charm\Support\Result;
use WP_Role;

/**
 * Represents a user role in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Role implements HasCoreRole, IsPersistable
{
    /**
     * Core role.
     *
     * @var ?Core\Role
     */
    protected ?Core\Role $coreRole = null;

    // *************************************************************************

    /**
     * Role constructor.
     *
     * @param Core\Role|array $coreRoleOrData
     */
    public function __construct(Core\Role|array $coreRoleOrData = [])
    {
        $this->coreRole = match (true) {
            $coreRoleOrData instanceof Core\Role => $coreRoleOrData,
            is_array($coreRoleOrData) => new Core\Role($coreRoleOrData),
            default => null
        };
    }

    // -------------------------------------------------------------------------

    /**
     * Get the core role instance.
     *
     * @return ?Core\Role
     */
    public function coreRole(): ?Core\Role
    {
        return $this->coreRole;
    }

    // -------------------------------------------------------------------------

    /**
     * Initialize the role.
     *
     * $key `string` -> Role slug
     *      `WP_Role` -> `WP_Role` instance
     *
     * @param string|WP_Role $key
     * @return ?static
     */
    public static function init(string|WP_Role $key): ?static
    {
        $coreRole = match (true) {
            is_string($key) => Core\Role::fromSlug($key),
            $key instanceof WP_Role => Core\Role::fromWpRole($key),
            default => null,
        };

        if ($coreRole === null) {
            return null;
        }

        return new static($coreRole);
    }

    // *************************************************************************

    /**
     * Get the roles.
     *
     * @return static[]
     */
    public static function get(): array
    {
        $coreRoles = Core\Role::get();

        $roles = [];

        foreach ($coreRoles as $coreRole) {
            $roles[] = new static($coreRole);
        }

        return $roles;
    }

    // *************************************************************************

    /**
     * Save the role.
     *
     * @return Result
     */
    public function save(): Result
    {
        return $this->coreRole()->save();
    }

    /**
     * Create the role.
     *
     * @return Result
     */
    public function create(): Result
    {
        return $this->coreRole()->create();
    }

    /**
     * Update the role.
     *
     * @return Result
     */
    public function update(): Result
    {
        return $this->coreRole()->update();
    }

    /**
     * Delete the role.
     *
     * @return Result
     */
    public function delete(): Result
    {
        return $this->coreRole()->delete();
    }

    // *************************************************************************

    /**
     * Get the slug.
     *
     * @return string administrator
     */
    public function getSlug(): string
    {
        return $this->coreRole()->getSlug();
    }

    // -------------------------------------------------------------------------

    /**
     * Get the name.
     *
     * @return string Administrator
     */
    public function getName(): string
    {
        return $this->coreRole()->getName();
    }

    /**
     * Set the name.
     *
     * @param string $name Administrator
     * @return static
     */
    public function setName(string $name): static
    {
        $this->coreRole()->setName($name);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the capabilities.
     *
     * @return array ['read' => true, 'manage_options' => true, ...]
     */
    public function getCapabilities(): array
    {
        return $this->coreRole()->getCapabilities();
    }

    /**
     * Add a capability.
     *
     * @param string $capability switch_themes
     * @return static
     */
    public function addCapability(string $capability): static
    {
        $this->coreRole()->addCapability($capability);

        return $this;
    }

    /**
     * Remove a capability.
     *
     * @param string $capability switch_themes
     * @return static
     */
    public function removeCapability(string $capability): static
    {
        $this->coreRole()->removeCapability($capability);

        return $this;
    }

    /**
     * Set the capabilities.
     *
     * @param array $capabilities ['read' => true, 'manage_options' => true, ...]
     * @return static
     */
    public function setCapabilities(array $capabilities): static
    {
        $this->coreRole()->setCapabilities($capabilities);

        return $this;
    }

    /**
     * Check whether the role has a capability.
     *
     * @param string $capability
     * @return bool
     */
    public function hasCapability(string $capability): bool
    {
        return $this->coreRole()->hasCapability($capability);
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether the role exists in the database.
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->coreRole()->exists();
    }
}