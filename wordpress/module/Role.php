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
    /************************************************************************************/
    // Properties

    /**
     * Name
     *
     * @var string
     */
    protected $name = '';

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

    /*----------------------------------------------------------------------------------*/

    /**
     * WordPress role
     *
     * @var WP_Role
     */
    private $wp_role = null;

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
        if (isset($data['name'])) {
            $this->name = $data['name'];
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
     * @see WP_Roles
     * @param string $name
     * @return static|null
     */
    public static function init(string $name)
    {
        $wp_roles = new WP_Roles();
        if (!isset($wp_roles->roles[$name])) {
            return null;
        }
        $wp_role = $wp_roles->roles[$name];
        $role = new static([
            'name' => $name,
            'display_name' => $wp_role['name'],
            'capabilities' => $wp_role['capabilities'],
        ]);
        $role->wp_role($wp_roles->role_objects[$name]);

        return $role;
    }

    /**
     * Get roles
     *
     * @see WP_Roles
     * @return static[]
     */
    public static function get(): array
    {
        $roles = [];
        $wp_roles = new WP_Roles();
        foreach ($wp_roles->roles as $name => $wp_role) {
             $role = new static([
                'name' => $name,
                'display_name' => $wp_role['name'],
                'capabilities' => $wp_role['capabilities'],
             ]);
             $role->wp_role($wp_roles->role_objects[$name]);
             $roles[] = $role;
        }

        return $roles;
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
        if (!get_role($this->name)) {
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
        if (!add_role($this->name, $this->display_name, $this->capabilities)) {
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
        remove_role($this->name);

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
        if ($this->name !== '') {
            $data['name'] = $this->name;
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
    // Object access methods

    /**
     * Get (or set) WordPress role
     *
     * @param WP_Role $role
     * @return WP_Role
     */
    protected function wp_role(WP_Role $role = null)
    {
        if ($role !== null) {
            $this->wp_role = $role;
        }
        return $this->wp_role;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get name
     *
     * @return string
     */
    public function get_name(): string
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function set_name(string $name): void
    {
        $this->name = $name;
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