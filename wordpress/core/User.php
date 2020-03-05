<?php

namespace Charm\WordPress\Core;

/**
 * Class User
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Core
 */
class User
{
    /**
     * ID
     *
     * @var int
     */
    private $id;

    /**
     * User login
     *
     * @var string
     */
    private $user_login;

    /**
     * User pass
     *
     * @var string
     */
    private $user_pass;

    /**
     * User nice name
     *
     * @var string
     */
    private $user_nicename;

    /**
     * User email
     *
     * @var string
     */
    private $user_email;

    /**
     * User URL
     *
     * @var string
     */
    private $user_url;

    /**
     * User registered
     *
     * @var string
     */
    private $user_registered;

    /**
     * User activation key
     *
     * @var string
     */
    private $user_activation_key;

    /**
     * User status
     *
     * @var string
     */
    private $user_status;

    /**
     * User display name
     *
     * @var string
     */
    private $display_name;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Default constructor
     *
     * @param array $data
     */
    public function __construct($data = [])
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
    public function load(array $data)
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
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
        if (isset($data['post_title'])) {
            $this->post_title = $data['post_title'];
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
     * @param null $key
     */
    public static function init($key = null)
    {
        $child = get_called_class();
        $post = new $child();
    }

    /************************************************************************************/
    // Action methods

    /**
     * Save user
     *
     * @return bool
     */
    public function save()
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
    public function create()
    {
        if (!$id = wp_insert_user($this->to_array())) {
            return false;
        }
        $this->id = $id;

        return true;
    }

    /**
     * Update existing user
     *
     * @see wp_update_user()
     * @return bool
     */
    public function update()
    {
        if (!$id = wp_update_user($this->to_array())) {
            return false;
        }

        return true;
    }

    /**
     * Update existing user
     *
     * @see wp_delete_user()
     * @return bool
     */
    public function delete()
    {
        if (!wp_delete_user($this->id)) {
            return false;
        }

        return true;
    }

    /************************************************************************************/
    // Conversion methods

    /**
     * Convert instance to array
     *
     * @return array
     */
    public function to_array()
    {
        $data = [];
        if ($this->id !== null) {
            $data['id'] = $this->id;
        }
        if ($this->user_login !== null) {
            $data['user_login'] = $this->user_login;
        }
        if ($this->user_pass !== null) {
            $data['user_pass'] = $this->user_pass;
        }
        if ($this->user_nicename !== null) {
            $data['user_nicename'] = $this->user_nicename;
        }
        if ($this->user_email !== null) {
            $data['user_email'] = $this->user_email;
        }
        if ($this->user_url !== null) {
            $data['user_url'] = $this->user_url;
        }
        if ($this->user_registered !== null) {
            $data['user_registered'] = $this->user_registered;
        }
        if ($this->user_activation_key !== null) {
            $data['user_activation_key'] = $this->user_activation_key;
        }
        if ($this->user_status !== null) {
            $data['user_status'] = $this->user_status;
        }
        if ($this->display_name !== null) {
            $data['display_name'] = $this->display_name;
        }

        return $data;
    }

    /**
     * Convert instance to JSON
     *
     * @return string
     */
    public function to_json()
    {
        return json_encode($this->to_array());
    }
}