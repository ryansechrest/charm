<?php

namespace Charm\Structures;

use Charm\Contracts\HasProxyRole;
use Charm\Contracts\IsPersistable;
use Charm\Support\Result;
use WP_Role;

/**
 * Represents a user role in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Role implements HasProxyRole, IsPersistable
{
    /**
     * Proxy role
     *
     * @var ?Proxy\Role
     */
    protected ?Proxy\Role $proxyRole = null;

    // *************************************************************************

    /**
     * Role constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->proxyRole = new Proxy\Role($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Get proxy role instance
     *
     * @return ?Proxy\Role
     */
    public function proxyRole(): ?Proxy\Role
    {
        return $this->proxyRole;
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
        $proxyRole = match (true) {
            is_string($key) => Proxy\Role::fromSlug($key),
            $key instanceof WP_Role => Proxy\Role::fromWpRole($key),
            default => null,
        };

        if ($proxyRole === null) {
            return null;
        }

        $role = new static();
        $role->proxyRole = $proxyRole;

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
        $wpRoles = Proxy\Role::get();

        $roles = [];

        foreach ($wpRoles as $wpRole) {
            $role = new static();
            $role->proxyRole = $wpRole;
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
        return $this->proxyRole()->save();
    }

    /**
     * Create new role
     *
     * @return Result
     */
    public function create(): Result
    {
        return $this->proxyRole()->create();
    }

    /**
     * Update existing role
     *
     * @return Result
     */
    public function update(): Result
    {
        return $this->proxyRole()->update();
    }

    /**
     * Delete role
     *
     * @return Result
     */
    public function delete(): Result
    {
        return $this->proxyRole()->delete();
    }

    // *************************************************************************

    /**
     * Get slug
     *
     * @return string administrator
     */
    public function getSlug(): string
    {
        return $this->proxyRole()->getSlug();
    }

    // -------------------------------------------------------------------------

    /**
     * Get name
     *
     * @return string Administrator
     */
    public function getName(): string
    {
        return $this->proxyRole()->getName();
    }

    /**
     * Set name
     *
     * @param string $name Administrator
     * @return static
     */
    public function setName(string $name): static
    {
        $this->proxyRole()->setName($name);

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
        return $this->proxyRole()->getCapabilities();
    }

    /**
     * Add capability
     *
     * @param string $capability switch_themes
     * @return static
     */
    public function addCapability(string $capability): static
    {
        $this->proxyRole()->addCapability($capability);

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
        $this->proxyRole()->removeCapability($capability);

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
        $this->proxyRole()->setCapabilities($capabilities);

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
        return $this->proxyRole()->hasCapability($capability);
    }

    // -------------------------------------------------------------------------

    /**
     * Whether role exists in database
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->proxyRole()->exists();
    }
}