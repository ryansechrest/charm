<?php

namespace Charm\App;

use Charm\App\Core\Entity;
use Charm\App\DataType\DateTime;
use Charm\WordPress\Meta\UserMeta;
use Charm\WordPress\User as WpUser;
use WP_User;

/**
 * Class User
 *
 * @author Ryan Sechrest
 * @package Charm\App
 */
class User extends Entity
{
    /**
     * WordPress user
     *
     * @var WpUser
     */
    protected $wp_user = null;

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize user
     *
     * @param int|null|string|WP_User $key
     * @return null|User
     */
    public static function init($key = null)
    {
        $data = [];
        $data['wp_user'] = WpUser::init($key);
        if ($data['wp_user'] === null) {
            return null;
        }

        return new User($data);
    }

    /**
     * Get users
     *
     * @todo Implement User::get()
     * @param array $params
     * @return User[]
     */
    public static function get(array $params): array
    {
        return [];
    }

    /************************************************************************************/
    // Conversion methods

    /**
     * Convert instance to array
     *
     * @return array
     */
    public function to_array(): array
    {
        $data = [
            'wp_user' => [],
        ];
        if ($this->wp_user !== null) {
            $data['wp_user'] = $this->wp_user->to_array();
        }

        return $data;
    }

    /**
     * Convert instance to JSON
     *
     * @return string
     */
    public function to_json(): string
    {
        return json_encode($this->to_array());
    }
    /**
     * Convert instance to stdClass
     *
     * @return object
     */
    public function to_object(): object
    {
        return (object) $this->to_array();
    }

    /************************************************************************************/
    // Action methods

    /**
     * Save user
     *
     * @todo Implement User->save()
     * @return bool
     */
    public function save(): bool
    {
        return false;
    }

    /**
     * Create user
     *
     * @todo Implement User->create()
     * @return bool
     */
    public function create(): bool
    {
        return false;
    }

    /**
     * Update user
     *
     * @todo Implement User->update()
     * @return bool
     */
    public function update(): bool
    {
        return false;
    }

    /**
     * Delete user
     *
     * @todo Implement User->delete()
     * @return bool
     */
    public function delete(): bool
    {
        return false;
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get WordPress user
     *
     * @return WpUser
     */
    public function user(): WpUser
    {
        return $this->wp_user;
    }

    /**
     * Get user meta
     *
     * @param string $key
     * @return null|UserMeta|UserMeta[]
     */
    public function meta(string $key)
    {
        return $this->wp_user->meta($key);
    }

    /**
     * Get registered date
     *
     * @return DateTime
     */
    public function registered_date(): DateTime
    {
        return $this->wp_user->get_user_registered();
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
        return $this->wp_user->get_id();
    }

    /*----------------------------------------------------------------------------------*/

    public function get_username(): string {
        return $this->wp_user->get_user_login();
    }

    public function set_username(string $username): void
    {
        $this->wp_user->set_user_login($username);
    }

    /*----------------------------------------------------------------------------------*/

    public function get_sanitized_username(): string {
        return $this->wp_user->get_user_nicename();
    }

    public function set_sanitized_username(string $username): void
    {
        $this->wp_user->set_user_nicename($username);
    }

    /*----------------------------------------------------------------------------------*/

    public function get_password(): string {
        return $this->wp_user->get_user_login();
    }

    public function set_password(string $password): void
    {
        $this->wp_user->set_user_pass($password);
    }

    /*----------------------------------------------------------------------------------*/

    public function get_display_name(): string {
        return $this->wp_user->get_display_name();
    }

    public function set_display_name(string $display_name): void
    {
        $this->wp_user->set_display_name($display_name);
    }

    /*----------------------------------------------------------------------------------*/

    public function get_email(): string {
        return $this->wp_user->get_user_email();
    }

    public function set_email(string $email): void
    {
        $this->wp_user->set_user_email($email);
    }

    /*----------------------------------------------------------------------------------*/

    public function get_url(): string {
        return $this->wp_user->get_user_url();
    }

    public function set_url(string $url): void
    {
        $this->wp_user->set_user_url($url);
    }

    /*----------------------------------------------------------------------------------*/

    public function get_activation_key(): string {
        return $this->wp_user->get_user_activation_key();
    }

    public function set_activation_key(string $activation_key): void
    {
        $this->wp_user->set_user_activation_key($activation_key);
    }
}