<?php

namespace Charm\WordPress\Module;

use WP_Role;
use WP_Roles;

/**
 * Class Role
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Module
 */
class Role
{
    /**
     * Role
     *
     * @var string
     */
    protected $role = '';

    /**
     * Display name
     *
     * @var string
     */
    protected $display_name = '';

    /**
     * Capabilities
     *
     * @var array
     */
    protected $capabilities = [];

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Role constructor
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (!is_array($data)) {
            return;
        }
        $this->load($data);
    }

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['role'])) {
            $this->role = $data['role'];
        }
        if (isset($data['display_name'])) {
            $this->display_name = $data['display_name'];
        }
        if (isset($data['capabilities'])) {
            $this->capabilities = $data['capabilities'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize role
     *
     * @param string $key
     * @return static|null
     */
    public static function init($key = '')
    {
        $roles = static::get();
        if (!is_array($roles) || count($roles) === 0) {
            return null;
        }
        $index = 0;
        $role = null;
        while ($role === null && $index < count($roles)) {
            if ($roles[$index]->get_role() === $key) {
                $role = $roles[$index];
            }
            $index++;
        }

        return $role;
    }

    /**
     * Get roles
     *
     * @see WP_Role
     * @see WP_Roles
     * @return static[]
     */
    public static function get()
    {
        $wp_roles = new WP_Roles();

        return array_map(function(string $display_name, WP_Role $wp_role) {
            return new static([
                'role' => $wp_role->name,
                'display_name' => $display_name,
                'capabilities' => $wp_role->capabilities,
            ]);
        }, $wp_roles->role_names, $wp_roles->role_objects);
    }

    /************************************************************************************/
    // Action methods

    /**
     * Save role
     *
     * @see get_role()
     * @return bool
     */
    public function save(): bool
    {
        if (!get_role($this->role)) {
            return $this->create();
        }

        return $this->update();
    }

    /**
     * Create new role
     *
     * @see add_role()
     * @return bool
     */
    public function create(): bool
    {
        if (!add_role($this->role, $this->display_name, $this->capabilities)) {
            return false;
        }

        return true;
    }

    /**
     * Update existing role
     *
     * @return bool
     */
    public function update(): bool
    {
        $this->delete();

        return $this->create();
    }

    /**
     * Delete role
     *
     * @see remove_role()
     * @return bool
     */
    public function delete(): bool
    {
        remove_role($this->role);

        return true;
    }

    /************************************************************************************/
    // Cast methods

    /**
     * Cast instance to array
     *
     * @return array
     */
    public function to_array(): array
    {
        $data = [];
        if ($this->role !== '') {
            $data['role'] = $this->role;
        }
        if ($this->display_name !== '') {
            $data['display_name'] = $this->display_name;
        }
        if ($this->capabilities !== '') {
            $data['capabilities'] = $this->capabilities;
        }

        return $data;
    }

    /**
     * Cast instance to JSON
     *
     * @return string
     */
    public function to_json(): string
    {
        return json_encode($this->to_array());
    }
    /**
     * Cast instance to object
     *
     * @return object
     */
    public function to_object(): object
    {
        return (object) $this->to_array();
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get role
     *
     * @return string
     */
    public function get_role(): string
    {
        return $this->role;
    }

    /**
     * Set role
     *
     * @param string $role
     */
    public function set_role(string $role): void
    {
        $this->role = $role;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get display name
     *
     * @return string
     */
    public function get_display_name(): string
    {
        return $this->display_name;
    }

    /**
     * Set display name
     *
     * @param string $display_name
     */
    public function set_display_name(string $display_name): void
    {
        $this->display_name = $display_name;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get capabilities
     *
     * @return array
     */
    public function get_capabilities(): array
    {
        return $this->capabilities;
    }

    /**
     * Add capabilities
     *
     * @param array $capabilities
     */
    public function add_capabilities(array $capabilities): void
    {
        foreach ($capabilities as $capability) {
            $this->add_capability($capability);
        }
    }

    /**
     * Add capability
     *
     * @param string $capability
     */
    public function add_capability(string $capability): void
    {
        $this->capabilities[$capability] = true;
    }

    /**
     * Has capabilities?
     *
     * @param array $capabilities
     * @return bool
     */
    public function has_capabilities(array $capabilities): bool
    {
        $index = 0;
        $has_all = true;
        while($has_all === true && $index < count($capabilities)) {
            if (!$this->has_capability($capabilities[$index])) {
                $has_all = false;
            }
            $index++;
        }

        return $has_all;
    }

    /**
     * Has capability?
     *
     * @param string $capability
     * @return bool
     */
    public function has_capability(string $capability): bool
    {
        if (!isset($this->capabilities[$capability])) {
            return false;
        }

        return true;
    }

    /**
     * Remove capabilities
     *
     * @param array $capabilities
     */
    public function remove_capabilities(array $capabilities): void
    {
        foreach ($capabilities as $capability) {
            $this->remove_capability($capability);
        }
    }

    /**
     * Remove capability
     *
     * @param string $capability
     */
    public function remove_capability(string $capability): void
    {
        unset($this->capabilities[$capability]);
    }

    /**
     * Set capabilities
     *
     * @param array $capabilities
     */
    public function set_capabilities(array $capabilities): void
    {
        $this->capabilities = $capabilities;
    }
}