<?php

namespace Charm\Structures\WordPress;

use Charm\Contracts\IsPersistable;
use Charm\Support\Result;
use WP_Role;

/**
 * Represents a user role in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Role implements IsPersistable
{
    /**
     * Role slug
     *
     * @var ?string
     */
    protected ?string $slug = null;

    /**
     * Role name
     *
     * @var ?string
     */
    protected ?string $name = null;

    /**
     * Role capabilities
     *
     * @var ?array
     */
    protected ?array $capabilities = null;

    // -------------------------------------------------------------------------

    /**
     * Whether role exists in database
     *
     * @var bool
     */
    protected bool $exists = false;

    // *************************************************************************

    /**
     * Role constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    /**
     * Load instance with data
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

    // *************************************************************************

    /**
     * Initialize role from slug
     *
     * @param string $slug administrator
     * @return ?static
     */
    public static function fromSlug(string $slug): ?static
    {
        $role = new static;
        $role->loadFromSlug($slug);

        return $role->slug ? $role : null;
    }

    /**
     * Initialize role from WP_Role
     *
     * @param WP_Role $wpRole
     * @return static
     */
    public static function fromWpRole(WP_Role $wpRole): static
    {
        $role = new static;
        $role->loadFromWpRole($wpRole);

        return $role;
    }

    // *************************************************************************

    /**
     * Get roles
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

        return array_map(function (WP_Role $wpRole) {
            return static::fromWpRole($wpRole);
        }, $wpRoles);
    }

    // *************************************************************************

    /**
     * Save role
     *
     * @return Result
     */
    public function save(): Result
    {
        return !$this->exists ? $this->create() : $this->update();
    }

    /**
     * Create new role
     *
     * @return Result
     * @see add_role()
     */
    public function create(): Result
    {
        if ($this->exists) {
            return Result::error(
                'role_exists',
                __('Role already exists.', 'charm')
            )->withData($this);
        }

        $result = add_role(
            $this->slug, $this->name, $this->capabilities
        );

        if (!$result instanceof WP_Role) {
            return Result::error(
                'add_role_failed',
                __('add_role() did not return WP_Role.', 'charm')
            )->withData($this);
        }

        $this->exists = true;

        return Result::success();
    }

    /**
     * Update existing role
     *
     * @return Result
     */
    public function update(): Result
    {
        if (!$this->exists) {
            return Result::error(
                'role_not_found',
                __('Role does not exist.', 'charm')
            )->withData($this);
        }

        $this->delete();

        return $this->create();
    }

    /**
     * Delete role
     *
     * @return Result
     * @see remove_role()
     */
    public function delete(): Result
    {
        if (!$this->exists) {
            return Result::error(
                'role_not_found',
                __('Role does not exist.', 'charm')
            )->withData($this);
        }

        remove_role($this->name);

        if (static::fromSlug($this->name) !== null) {
            return Result::error(
                'role_not_deleted',
                __('Role could not be deleted.', 'charm')
            )->withData($this);
        }

        $this->exists = false;

        return Result::success();
    }

    // *************************************************************************

    /**
     * Get slug
     *
     * @return string administrator
     */
    public function getSlug(): string
    {
        return $this->slug ?? '';
    }

    // -------------------------------------------------------------------------

    /**
     * Get name
     *
     * @return string Administrator
     */
    public function getName(): string
    {
        return $this->name ?? '';
    }

    /**
     * Set name
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
     * Get capabilities
     *
     * @return array ['read' => true, 'manage_options' => true, ...]
     */
    public function getCapabilities(): array
    {
        return $this->capabilities ?? [];
    }

    /**
     * Add capability
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
     * Remove capability
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
     * Set capabilities
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
     * Whether role has capability
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
     * Whether role exists in database
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->exists;
    }

    // *************************************************************************

    /**
     * Load instance from slug
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

        if (!$role = get_role($slug)) {
            return;
        }

        $this->loadFromWpRole($role);
    }

    /**
     * Load instance from WP_Role
     *
     * @param WP_Role $wpRole
     * @return void
     * @see wp_roles()
     */
    protected function loadFromWpRole(WP_Role $wpRole): void
    {
        $roles = wp_roles();

        $this->slug = $wpRole->name;
        $this->name = $roles->role_names[$wpRole->name] ?: '';
        $this->capabilities = $wpRole->capabilities;
        $this->exists = true;
    }
}