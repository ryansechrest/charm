<?php

namespace Charm\Structures\Proxy;

use Charm\Contracts\IsPersistable;
use Charm\Contracts\WordPress\HasWpRole;
use Charm\Support\Result;
use WP_Role;

/**
 * Represents a proxy user role in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Role implements HasWpRole, IsPersistable
{
    /**
     * Role slug.
     *
     * @var ?string
     */
    protected ?string $slug = null;

    /**
     * Role name.
     *
     * @var ?string
     */
    protected ?string $name = null;

    /**
     * Role capabilities.
     *
     * @var ?array
     */
    protected ?array $capabilities = null;

    // -------------------------------------------------------------------------

    /**
     * Whether the role exists in the database.
     *
     * @var bool
     */
    protected bool $exists = false;

    // -------------------------------------------------------------------------

    /**
     * `WP_Role` instance.
     *
     * @var ?WP_Role
     */
    protected ?WP_Role $wpRole = null;

    // *************************************************************************

    /**
     * Role constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    /**
     * Load the instance with data.
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['slug'])) {
            $this->slug = $data['slug'];
        }

        if (isset($data['name'])) {
            $this->name = $data['name'];
        }

        if (isset($data['capabilities'])) {
            $this->capabilities = $data['capabilities'];
        }

        if (isset($data['exists'])) {
            $this->exists = (bool) $data['exists'];
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Get the `WP_Role` instance.
     *
     * @return ?WP_Role
     */
    public function wpRole(): ?WP_Role
    {
        return $this->wpRole;
    }

    // *************************************************************************

    /**
     * Initialize the role from a slug.
     *
     * @param string $slug administrator
     * @return ?static
     */
    public static function fromSlug(string $slug): ?static
    {
        $role = new static();
        $role->loadFromSlug($slug);

        return $role->slug ? $role : null;
    }

    /**
     * Initialize the role from a `WP_Role` instance.
     *
     * @param WP_Role $wpRole
     * @return static
     */
    public static function fromWpRole(WP_Role $wpRole): static
    {
        $role = new static();
        $role->loadFromWpRole($wpRole);

        return $role;
    }

    // *************************************************************************

    /**
     * Get the roles.
     *
     * @return static[]
     * @see wp_roles()
     */
    public static function get(): array
    {
        $wpRoles = wp_roles()->role_objects;

        if (count($wpRoles) === 0) {
            return [];
        }

        return array_map(
            fn(WP_Role $wpRole) => static::fromWpRole($wpRole),
            $wpRoles
        );
    }

    // *************************************************************************

    /**
     * Save the role.
     *
     * @return Result
     */
    public function save(): Result
    {
        return !$this->exists ? $this->create() : $this->update();
    }

    /**
     * Create the role.
     *
     * @return Result
     * @see add_role()
     */
    public function create(): Result
    {
        if ($this->exists) {
            return Result::error(
                code: 'role_exists',
                message: __('Role already exists.', 'charm')
            )->withData($this);
        }

        $result = add_role(
            role: $this->slug,
            display_name: $this->name,
            capabilities: $this->capabilities
        );

        if (!$result instanceof WP_Role) {
            return Result::error(
                code: 'add_role_failed',
                message: __('add_role() did not return WP_Role.', 'charm')
            )->withData($this);
        }

        $this->exists = true;

        return Result::success();
    }

    /**
     * Update the role.
     *
     * @return Result
     */
    public function update(): Result
    {
        if (!$this->exists) {
            return Result::error(
                code: 'role_not_found',
                message: __('Role does not exist.', 'charm')
            )->withData($this);
        }

        $this->delete();

        return $this->create();
    }

    /**
     * Delete the role.
     *
     * @return Result
     * @see remove_role()
     */
    public function delete(): Result
    {
        if (!$this->exists) {
            return Result::error(
                code: 'role_not_found',
                message: __('Role does not exist.', 'charm')
            )->withData($this);
        }

        remove_role(role: $this->name);

        if (static::fromSlug($this->name) !== null) {
            return Result::error(
                code: 'role_not_deleted',
                message: __('Role could not be deleted.', 'charm')
            )->withData($this);
        }

        $this->exists = false;

        return Result::success();
    }

    // *************************************************************************

    /**
     * Get the slug.
     *
     * @return string administrator
     */
    public function getSlug(): string
    {
        return $this->slug ?? '';
    }

    // -------------------------------------------------------------------------

    /**
     * Get the name.
     *
     * @return string Administrator
     */
    public function getName(): string
    {
        return $this->name ?? '';
    }

    /**
     * Set the name.
     *
     * @param string $name Administrator
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;

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
        return $this->capabilities ?? [];
    }

    /**
     * Add a capability.
     *
     * @param string $capability switch_themes
     * @return static
     */
    public function addCapability(string $capability): static
    {
        $this->capabilities[$capability] = true;

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
        unset($this->capabilities[$capability]);

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
        $this->capabilities = $capabilities;

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
        if (!isset($this->capabilities[$capability])) {
            return false;
        }

        return true;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether the role exists in the database.
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->exists;
    }

    // *************************************************************************

    /**
     * Load the instance from a slug.
     *
     * @param string $slug
     * @return void
     * @see get_role()
     */
    protected function loadFromSlug(string $slug): void
    {
        if ($slug === '') {
            return;
        }

        if (!$role = get_role(role: $slug)) {
            return;
        }

        $this->loadFromWpRole($role);
    }

    /**
     * Load the instance from a `WP_Role` instance.
     *
     * @param WP_Role $wpRole
     * @return void
     * @see wp_roles()
     */
    protected function loadFromWpRole(WP_Role $wpRole): void
    {
        $roles = wp_roles();

        $this->wpRole = $wpRole;

        $this->slug = $wpRole->name;
        $this->name = $roles->role_names[$wpRole->name] ?: '';
        $this->capabilities = $wpRole->capabilities;
        $this->exists = true;
    }
}