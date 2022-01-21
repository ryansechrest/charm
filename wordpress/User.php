<?php

namespace Charm\WordPress;

use WP_User;
use WP_User_Query;

/**
 * Class User
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress
 */
class User
{
    /************************************************************************************/
    // Properties

    /**
     * ID
     *
     * @var int
     */
    protected int $id = 0;

    /**
     * User login
     *
     * @var string
     */
    protected string $user_login = '';

    /**
     * User pass
     *
     * @var string
     */
    protected string $user_pass = '';

    /**
     * User nice name
     *
     * @var string
     */
    protected string $user_nicename = '';

    /**
     * User email
     *
     * @var string
     */
    protected string $user_email = '';

    /**
     * User URL
     *
     * @var string
     */
    protected string $user_url = '';

    /**
     * User registered
     *
     * @var string
     */
    protected string $user_registered = '';

    /**
     * User activation key
     *
     * @var string
     */
    protected string $user_activation_key = '';

    /**
     * User status
     *
     * @var string
     */
    protected string $user_status = '';

    /**
     * User display name
     *
     * @var string
     */
    protected string $display_name = '';

    /*----------------------------------------------------------------------------------*/

    /**
     * WordPress user
     *
     * @var WP_User|null
     */
    private ?WP_User $wp_user = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * User constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (count($data) === 0) {
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
        if (isset($data['id'])) {
            $this->id = (int) $data['id'];
        }
        if (isset($data['user_login'])) {
            $this->user_login = $data['user_login'];
        }
        if (isset($data['user_pass'])) {
            $this->user_pass = $data['user_pass'];
        }
        if (isset($data['user_nicename'])) {
            $this->user_nicename = $data['user_nicename'];
        }
        if (isset($data['user_email'])) {
            $this->user_email = $data['user_email'];
        }
        if (isset($data['user_url'])) {
            $this->user_url = $data['user_url'];
        }
        if (isset($data['user_registered'])) {
            $this->user_registered = $data['user_registered'];
        }
        if (isset($data['user_activation_key'])) {
            $this->user_activation_key = $data['user_activation_key'];
        }
        if (isset($data['user_status'])) {
            $this->user_status = $data['user_status'];
        }
        if (isset($data['display_name'])) {
            $this->display_name = $data['display_name'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize user
     *
     * @param int|string|WP_User|null $key
     * @return static|null
     */
    public static function init(int|string|WP_User $key = null): ?User
    {
        $user = new static();
        if (is_int($key) || is_numeric($key)) {
            $user->load_from_id($key);
        } elseif (is_string($key) && !is_email($key)) {
            $user->load_from_login($key);
        } elseif (is_string($key) && is_email($key)) {
            $user->load_from_email($key);
        } elseif (is_object($key) && get_class($key) === 'WP_User') {
            $user->load_from_user($key);
        } else {
            $user->load_from_global_user();
        }
        if ($user->get_id() === 0) {
            return null;
        }

        return $user;
    }

    /**
     * Get users
     *
     * @param array $params
     * @return static[]
     */
    public static function get(array $params): array
    {
        $query = static::query($params);
        if ($query->get_total() === 0) {
            return [];
        }

        return array_map(function(WP_User $user) {
            return static::init($user);
        }, $query->get_results());
    }

    /**
     * Query using WP_User_Query
     *
     * @param array $params
     * @return WP_User_Query
     */
    public static function query(array $params): WP_User_Query
    {
        return new WP_User_Query($params);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is user logged in?
     *
     * @see is_user_logged_in()
     * @return bool
     */
    public static function is_logged_in(): bool
    {
        return is_user_logged_in();
    }

    /************************************************************************************/
    // Protected load methods

    /**
     * Load instance from ID
     *
     * @param int $id
     */
    protected function load_from_id(int $id): void
    {
        if (!$user = static::get_by('id', $id)) {
            return;
        }

        $this->load_from_user($user);
    }

    /**
     * Load instance from login
     *
     * @param string $login
     */
    protected function load_from_login(string $login): void
    {
        if (!$user = static::get_by('login', $login)) {
            return;
        }
        $this->load_from_user($user);
    }

    /**
     * Load instance from email
     *
     * @param string $email
     */
    protected function load_from_email(string $email): void
    {
        if (!$user = static::get_by('email', $email)) {
            return;
        }
        $this->load_from_user($user);
    }

    /**
     * Load instance from global WP_User object
     *
     * @see wp_get_current_user()
     */
    protected function load_from_global_user(): void
    {
        if (!$user = wp_get_current_user()) {
            return;
        }
        if ($user->ID === 0) {
            return;
        }
        $this->load_from_user($user);
    }

    /**
     * Load instance from WP_User object
     *
     * @see WP_User
     * @param WP_User $user
     */
    protected function load_from_user(WP_User $user): void
    {
        $this->id = (int) $user->data->ID;
        $this->user_login = $user->data->user_login;
        $this->user_pass = $user->data->user_pass;
        $this->user_nicename = $user->data->user_nicename;
        $this->user_email = $user->data->user_email;
        $this->user_url = $user->data->user_url;
        $this->user_registered = $user->data->user_registered;
        $this->user_activation_key = $user->data->user_activation_key;
        $this->user_status = (int) $user->data->user_status;
        $this->display_name = $user->data->display_name;
        $this->wp_user = $user;
    }

    /**
     * Get WP_User by field
     *
     * @param string $field
     * @param int|string $value
     * @return WP_User|null
     */
    private function get_by(string $field, int|string $value): ?WP_User
    {
        $data = WP_User::get_data_by($field, $value);
        if ($data === false) {
            return null;
        }
        $user = new WP_User();
        $user->init($data);

        return $user;
    }

    /**
     * Reload instance from database
     */
    protected function reload(): void
    {
        if (!$this->id) {
            return;
        }
        $this->load_from_id($this->id);
    }

    /************************************************************************************/
    // Action methods

    /**
     * Save user
     *
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->id) {
            return $this->create();
        }

        return $this->update();
    }

    /**
     * Create new user
     *
     * @see wp_insert_user()
     * @return bool
     */
    public function create(): bool
    {
        if (!$id = wp_insert_user($this->to_array())) {
            return false;
        }
        $this->id = $id;
        $this->reload();

        return true;
    }

    /**
     * Update existing user
     *
     * @see wp_update_user()
     * @return bool
     */
    public function update(): bool
    {
        if (!$id = wp_update_user($this->to_array())) {
            return false;
        }
        $this->reload();

        return true;
    }

    /**
     * Delete user
     *
     * @see wp_delete_user()
     * @return bool
     */
    public function delete(): bool
    {
        if (!wp_delete_user($this->id)) {
            return false;
        }

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
        if ($this->id !== 0) {
            $data['ID'] = $this->id;
        }
        if ($this->user_login !== '') {
            $data['user_login'] = $this->user_login;
        }
        if ($this->user_pass !== '') {
            $data['user_pass'] = $this->user_pass;
        }
        if ($this->user_nicename !== '') {
            $data['user_nicename'] = $this->user_nicename;
        }
        if ($this->user_email !== '') {
            $data['user_email'] = $this->user_email;
        }
        if ($this->user_url !== '') {
            $data['user_url'] = $this->user_url;
        }
        if ($this->user_registered !== '') {
            $data['user_registered'] = $this->user_registered;
        }
        if ($this->user_activation_key !== '') {
            $data['user_activation_key'] = $this->user_activation_key;
        }
        if ($this->user_status !== '') {
            $data['user_status'] = $this->user_status;
        }
        if ($this->display_name !== '') {
            $data['display_name'] = $this->display_name;
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
     * Get (or set) WordPress user
     *
     * @param null|WP_User $user
     * @return WP_User
     */
    protected function wp_user(WP_User $user = null): WP_User
    {
        if ($user !== null) {
            $this->wp_user = $user;
        }

        return $this->wp_user;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get ID
     *
     * @return int
     */
    public function get_id(): int
    {
        return $this->id;
    }

    /**
     * Set ID
     *
     * @param int $id
     */
    public function set_id(int $id): void
    {
        $this->id = $id;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get user login
     *
     * @return string
     */
    public function get_user_login(): string
    {
        return $this->user_login;
    }

    /**
     * Set user login
     *
     * @param string $user_login
     */
    public function set_user_login(string $user_login): void
    {
        $this->user_login = $user_login;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get user pass
     *
     * @return string
     */
    public function get_user_pass(): string
    {
        return $this->user_pass;
    }

    /**
     * Set user pass
     *
     * @param string $user_pass
     */
    public function set_user_pass(string $user_pass): void
    {
        $this->user_pass = $user_pass;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get user nicename
     *
     * @return string
     */
    public function get_user_nicename(): string
    {
        return $this->user_nicename;
    }

    /**
     * Set user nicename
     *
     * @param string $user_nicename
     */
    public function set_user_nicename(string $user_nicename): void
    {
        $this->user_nicename = $user_nicename;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get user email
     *
     * @return string
     */
    public function get_user_email(): string
    {
        return $this->user_email;
    }

    /**
     * Set user email
     *
     * @param string $user_email
     */
    public function set_user_email(string $user_email): void
    {
        $this->user_email = $user_email;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get user URL
     *
     * @return string
     */
    public function get_user_url(): string
    {
        return $this->user_url;
    }

    /**
     * Set user URL
     *
     * @param string $user_url
     */
    public function set_user_url(string $user_url): void
    {
        $this->user_url = $user_url;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get user registered
     *
     * @return string
     */
    public function get_user_registered(): string
    {
        return $this->user_registered;
    }

    /**
     * Set user registered
     *
     * @param string $user_registered
     */
    public function set_user_registered(string $user_registered): void
    {
        $this->user_registered = $user_registered;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get user activation key
     *
     * @return string
     */
    public function get_user_activation_key(): string
    {
        return $this->user_activation_key;
    }

    /**
     * Set user activation key
     *
     * @param string $user_activation_key
     */
    public function set_user_activation_key(string $user_activation_key): void
    {
        $this->user_activation_key = $user_activation_key;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get user status
     *
     * @return string
     */
    public function get_user_status(): string
    {
        return $this->user_status;
    }

    /**
     * Set user status
     *
     * @param string $user_status
     */
    public function set_user_status(string $user_status): void
    {
        $this->user_status = $user_status;
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
}