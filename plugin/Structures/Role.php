<?php

namespace Charm\Structures;

use Charm\Contracts\HasWpRole;
use Charm\Contracts\IsPersistable;
use Charm\Support\Result;
use WP_Role;

/**
 * Represents a user role in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Role implements HasWpRole, IsPersistable
{
    /**
     * WordPress role
     *
     * @var ?WordPress\Role
     */
    protected ?WordPress\Role $wpRole = null;

    // *************************************************************************

    /**
     * Role constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->wpRole = new WordPress\Role($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Get WordPress role instance
     *
     * @return ?WordPress\Role
     */
    public function wp(): ?WordPress\Role
    {
        return $this->wpRole;
    }

    // -------------------------------------------------------------------------

    /**
     * Initialize role
     *
     * string  -> Role Slug
     * WP_Role -> WP_Role instance
     *
     * @param string|WP_Role $key
     * @return ?static
     */
    public static function init(string|WP_Role $key): ?static
    {
        $wpRole = match (true) {
            is_string($key) => WordPress\Role::fromSlug($key),
            $key instanceof WP_Role => WordPress\Role::fromWpRole($key),
            default => null,
        };

        if ($wpRole === null) {
            return null;
        }

        $role = new static();
        $role->wpRole = $wpRole;

        return $role;
    }

    // *************************************************************************

    /**
     * Get roles
     *
     * @return static[]
     */
    public static function get(): array
    {
        $wpRoles = WordPress\Role::get();

        $roles = [];

        foreach ($wpRoles as $wpRole) {
            $role = new static();
            $role->wpRole = $wpRole;
            $roles[] = $role;
        }

        return $roles;
    }

    // *************************************************************************

    /**
     * Save role
     *
     * @return Result
     */
    public function save(): Result
    {
        return $this->wp()->save();
    }

    /**
     * Create new role
     *
     * @return Result
     */
    public function create(): Result
    {
        return $this->wp()->create();
    }

    /**
     * Update existing role
     *
     * @return Result
     */
    public function update(): Result
    {
        return $this->wp()->update();
    }

    /**
     * Delete role
     *
     * @return Result
     */
    public function delete(): Result
    {
        return $this->wp()->delete();
    }

    // *************************************************************************

    /**
     * Get slug
     *
     * @return string administrator
     */
    public function getSlug(): string
    {
        return $this->wp()->getSlug();
    }

    // -------------------------------------------------------------------------

    /**
     * Get name
     *
     * @return string Administrator
     */
    public function getName(): string
    {
        return $this->wp()->getName();
    }

    /**
     * Set name
     *
     * @param string $name Administrator
     * @return static
     */
    public function setName(string $name): static
    {
        $this->wp()->setName($name);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get capabilities
     *
     * @return array ['read' => true, 'manage_options' => true, ...]
     */
    public function getCapabilities(): array
    {
        return $this->wp()->getCapabilities();
    }

    /**
     * Add capability
     *
     * @param string $capability switch_themes
     * @return static
     */
    public function addCapability(string $capability): static
    {
        $this->wp()->addCapability($capability);

        return $this;
    }

    /**
     * Remove capability
     *
     * @param string $capability switch_themes
     * @return static
     */
    public function removeCapability(string $capability): static
    {
        $this->wp()->removeCapability($capability);

        return $this;
    }

    /**
     * Set capabilities
     *
     * @param array $capabilities ['read' => true, 'manage_options' => true, ...]
     * @return static
     */
    public function setCapabilities(array $capabilities): static
    {
        $this->wp()->setCapabilities($capabilities);

        return $this;
    }

    /**
     * Whether role has capability
     *
     * @param string $capability
     * @return bool
     */
    public function hasCapability(string $capability): bool
    {
        return $this->wp()->hasCapability($capability);
    }

    // -------------------------------------------------------------------------

    /**
     * Whether role exists in database
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->wp()->exists();
    }
}